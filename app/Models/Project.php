<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Str;


class Project extends BaseModel
{
    protected $table = 'admin_projects';
    protected $fillable = ['title','folder','background','logo','streamlist','filelist'];
    public function download($id){
        $project = $this -> find($id);
        $folder =  $project -> folder;
         if( $project->download !== NULL){
             return response()->download(getRealPath($project->download),  $folder.date("YmdHi"). '.zip' ,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
         } else{
             //重新打包
             $this -> package($id);
             $this -> download($id);
         }
    }

    public function package($id){
        $out = ['code'=> 0, 'msg'=>'打包完成', 'data'=>''];
        $project = $this -> find($id);
        $material = new Material;
        $zipper = new Zipper;
        $folder =  $project -> folder;
        $config =  json_decode($project->filelist,true);
        $modelList = $config['file'];
        $modelQuality = $config['model_quality'];
        $textureQuality = $config['texture_quality'];
        $admin = new Admin;
        $zip_path = $admin->userId().'/'.$folder.'/'.Str::random().'.zip';
        foreach ($modelList as $modelFile){
            $model = $material -> where('folder',$folder)->where('type','model')->where('filename',$modelFile)->first();
            $compressModel = $model->compressed->where('desc', $modelQuality)->first();
            $zipper -> make(getRealPath($zip_path))->folder($folder)->folder('model')->add( getRealPath($compressModel->path));
            foreach ($material -> where('folder',$folder)->where('type','texture')->where('filename','like',$modelFile. '%')->get() as $texture) {
                $compressTexture = $texture->compressed->where('desc',$textureQuality)->first();
                if($compressTexture){
                    $zipper -> make(getRealPath($zip_path))->folder($folder)->folder('texture')->add(getRealPath($compressTexture->path));
                } else{
                    Log::addLogs('下载失败（不存在对应的贴图）','/project/download');
                    $out['code']= -1;
                    $out['msg']='打包失败，不存在对应的贴图';
                }
            }
        }
        $zipper->close();
        $project -> download =  $zip_path;
        if( $project->save()){
            $out['data']= route('admin.project.download',$id);
            Log::addLogs('打包成功','/project/download');
        } else{
            $out['code']= -1;
            $out['msg']='打包失败';
            Log::addLogs('打包失败','/project/download');
        }
        return response()->json($out)->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }


    /**
     * @param $modelIndex
     * @param $folder
     * @param $modelQuality
     * @param $textureQuality
     * @param bool $cdn
     * @return array|bool
     */
    public function getProjectFile($modelIndex, $folder, $modelQuality, $textureQuality, $cdn = false){
        $material = new Material;
        $data = array();
        foreach ($modelIndex as $key => $modelFile){
            $model = $material->where('folder',$folder)->where('type','model')->where('filename',$modelFile)->first();
            $compressModel = $model->compressed->where('desc', $modelQuality)->first();
            if(!$model || !$compressModel){
                return false;
            }
            $data[$key] = array(
                'index' =>  $modelFile,
                'model' =>  ['url'=>asset('storage/'.$compressModel->path),'local'=>'static/model/'.$compressModel->path],
            );
            if($cdn){
                $data[$key]['model']['cdn'] = 'https://cdn.model.dgene.com/fusion/'.$compressModel->path;
            }
            foreach ( $material->where('folder',$folder)->where('type','texture')->where('filename','like',$modelFile. '%')->get() as $texKey => $item) {
                $compressTexture = $item->compressed->where('desc',$textureQuality)->first();
                if(!$compressTexture){
                    return false;
                }
                $data[$key]['texture'][$texKey] = array(
                    'name'  =>  $compressTexture->filename,
                    'url'   =>  asset('storage/'.$compressTexture->path),
                    'local' =>  'static/texture/'.$compressTexture->path
                );
                if($cdn){
                    $data[$key]['texture'][$texKey]['cdn'] = 'https://cdn.model.dgene.com/fusion/'.$compressTexture->path;
                }
            }
        }
        return $data;
    }
}
