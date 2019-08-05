<?php

namespace App\Jobs;

use App\Models\Material;
use App\Models\MaterialCompress;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Log;
use phpDocumentor\Reflection\Types\Integer;

class ImportModel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $sign;
    private $folder;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sign)
    {
        $this->sign = $sign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sign = $this->sign;
        $material = new Material();
        $data = $material->where('sign',$sign)->first();
        $this->folder = $data->user_id.'/'.$data->folder;
        if(in_array( $data -> suffix, explode(',', config('admin.uncompress_model_extensions')))){
            $inputData = [
                'material_id'  =>   $data -> id,
                'filename'     =>   $data -> filename,
                'suffix'       =>   $data -> suffix,
                'path'         =>   $data -> path,
                'type'         =>   $data -> type,
                'size'         =>   $data -> size,
                'desc'         =>   $data -> desc,
                'status'       =>   1,
            ];
            $this -> storeFile($inputData);
        }else{
            switch ($data -> type){
                case 'model':
                    $this -> compressModel($data);
                    break;
                case 'texture':
                    $this -> compressTexture($data);
                    break;
                case 'other':

                    break;
                default:
                    break;
            }
        }
    }

    /**
     * compress Model file
     * @param $data
     * @param bool $addOrginal
     */
    public function compressModel($data,$addOriginal = true){
        $config =  json_decode($data->config,true);
        $needCompress =  $config['is_cutface'];//on or off
        $model_config_list = $needCompress == "on" && isset($config["model_cutface_list"])? explode(',',$config["model_cutface_list"]) : [];//['5k','1w','5w']
        if( $addOriginal ) array_push($model_config_list,"original");
        $model_compress = $config['model_compress'];//'dgene'
        foreach ($model_config_list as $item) {
            if( $item == "original"){
                if($model_compress == "dgene" && $needCompress == "on"){
                    $compressedFile = $this -> obj2DGene($data -> path, false);
                }elseif ($needCompress == "off"){
                    $compressedFile = $data -> path;
                    $model_compress = $data -> suffix;
                }else{
                    Log::addLogs('格式不支持'.trans('fzs.material.process_fail'),'/material/upload');
                }
            }else{
                $mlx_script = $this -> getCutScript($item);
                $cutObjFile = $this -> cutFace($data -> path, $mlx_script, false);
                if($model_compress == "dgene"){
                    $compressedFile = $this -> obj2DGene($cutObjFile, true);
                }else{
                    Log::addLogs('格式不支持'.trans('fzs.material.process_fail'),'/material/upload');
                }
            }
            $inputData = [
                'material_id'  =>   $data -> id,
                'filename'     =>   $data -> filename,
                'suffix'       =>   $model_compress,
                'path'         =>   $compressedFile,
                'type'         =>   'model',
                'size'         =>   Storage::size($compressedFile),
                'desc'         =>   $item,
                'status'       =>   1,
            ];
            $this -> storeFile($inputData);
        }
    }


    /**
     * compress Texture file
     * @param $data
     * @param bool $addOriginal
     */
    public function compressTexture($data ,$addOriginal = true){
        $config =  json_decode($data->config,true);
        $needCompress =   $config['is_resize_image'];
        $texture_config_list = $needCompress == "on" && isset($config["texture_resize_list"]) ? explode(',',$config["texture_resize_list"]): [];//[512,1024,2048,4096]
        if( $addOriginal ) array_push($texture_config_list,"original");
        $originalWidth = \Image::make($this->getRealPath($data->path))->width();
        $originalHeight = \Image::make($this->getRealPath($data->path))->height();
        if( $needCompress == "on" ){
            $texture_compress =  $config['texture_compress'];//'jpg,webp'
            $originalOutput = $this -> resizeTexture($data->path, $originalWidth, $originalHeight, 80, $texture_compress);
        } else {
            $originalOutput = $data -> path;
            $texture_compress = $data -> suffix;
        }
        foreach ($texture_config_list as $item) {
            if( $item == "original"){
                $compressedFile = $originalOutput;
                $size = $originalWidth.'*'. $originalHeight;
            } else{
                $intItem = intval($item);
                \Log::info('intItem:'.$intItem);
                if($intItem < $originalWidth){
                    $rate = $originalWidth/$intItem; //考虑到原图是4096*2048  这种情况
                    $compressedFile = $this -> resizeTexture($data->path,  $intItem,  $originalHeight/$rate, 80, $texture_compress);
                    $size = $intItem.'*'. $originalHeight/$rate;
                    \Log::info('finish-:'. $item. $compressedFile);
                } else{
                    $compressedFile = $originalOutput;
                    $size = $originalWidth.'*'. $originalHeight;
                }
            }

            $inputData = [
                'material_id'  =>   $data -> id,
                'filename'     =>   $data -> filename,
                'suffix'       =>   $texture_compress,
                'path'         =>   $compressedFile,
                'type'         =>   'texture',
                'size'         =>   Storage::size($compressedFile).','.$size,
                'desc'         =>   $item,
                'status'       =>   1,
            ];
            $this -> storeFile($inputData);
        }
    }

    /**
     * @param $path
     * @return string: fullpath
     */
    function getRealPath($path){
        return config('filesystems.disks.public.root').'/'.$path;
    }

    /**
     * @param $inputFile: storage default filesystem path
     * From obj file to dgene file, and store it in the same path as OBJ.
     */
    function obj2DGene($inputFile,$delete = true){
        $outputFile = str_replace('.obj', '.dgene', $inputFile);
        $cmdCompressObj = "draco_encoder -i '".$this->getRealPath($inputFile)."' -o '". $this->getRealPath($outputFile)."'";
        try{
            @exec("$cmdCompressObj 2>&1",$outCompressObj);
        }catch (\Exception $e){
            Log::addLogs('压缩模型'.trans('fzs.material.process_fail'),'/material/upload');
            return false;
        }
        if(Storage::exists( $outputFile)) {
            if($delete) Storage::delete([$inputFile, $inputFile . '.mtl']);
            return $outputFile;
        } else {
            Log::addLogs('压缩模型'.trans('fzs.material.process_fail'),'/material/upload');
            return false;
        }
    }

    /**
     * @param $level
     * @return bool|string
     */
    public function getCutScript($level){
        $scriptFile = config_path('cutface_'.$level.'.mlx');
        if(file_exists( $scriptFile )){
            return  $scriptFile;
        } else{
            Log::addLogs('减面shell未找到','/material/upload');
            return false;
        }
    }

    /**
     * @param $inputFile
     * @param $scriptFile
     * @return string $outputFile
     */
    public function cutFace($inputFile, $scriptFile, $delete = true){
        $outputFile = $this->folder.'/'.Str::random(32).'.obj';
        $cmdCutFace = "xvfb-run -a -s '-screen 0 800x600x24' meshlabserver -i '".$this->getRealPath($inputFile)."' -o '". $this->getRealPath($outputFile)."'  -s '".$scriptFile."' -om vn vt wt";
        try{
            @exec("$cmdCutFace 2>&1",$outputCutFace);
            \Log::info('cmdCutFace:'.$cmdCutFace);
        }catch (\Exception $e){
            Log::addLogs(trans('减面'.'fzs.material.process_fail'),'/material/upload');
            return false;
        }

        if(Storage::exists( $outputFile)) {
            if($delete) Storage::delete($inputFile);
            return $outputFile;
        } else {
            Log::addLogs(trans('减面'.'fzs.material.process_fail'),'/material/upload');
            return false;
        }
    }

    /**
     * @param $inputFile
     * @param Integer $width
     * @param Integer $height
     * @param string $ext
     * @return bool|string
     */
    public function resizeTexture($inputFile, $width, $height, $quality = 90 ,$ext = "jpg"){
        $outputFile = $this->folder.'/'.Str::random(32).'.'.$ext;
        try{
            \Image::make($this->getRealPath($inputFile)) -> resize($width, $height) -> save($this->getRealPath($outputFile), $quality);
        }catch (\Exception $e){
            Log::addLogs(trans('压缩贴图'.'fzs.material.process_fail'),'/material/upload');
            return false;
        }
        if(Storage::exists( $outputFile )) {
            return $outputFile;
        }else {
            Log::addLogs(trans('压缩贴图'.'fzs.material.process_fail'),'/material/upload');
            return false;
        }
    }

    /**
     * @param $data
     */
    public function storeFile($data){
        \Log::info(json_encode($data));
        $materialCompress = new MaterialCompress();
        $res = $materialCompress->checkStore($data);
        if($res){Log::addLogs(trans('fzs.material.process_success').$data['filename'],'/material/upload');}
        else {Log::addLogs(trans('fzs.material.process_fail').$data['filename'],'/material/upload');}
    }
}
