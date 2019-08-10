<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Illuminate\Support\Str;

class Material extends BaseModel
{
    protected $table = 'admin_materials';
    protected $fillable = ['user_id','folder','filename','suffix','sign','type','path','size','desc','config'];
    public function compressed(){
        return $this->hasMany(MaterialCompress::class,'material_id','id');
    }

    public function getModelList($folder,$type){
        return $this->where('folder',$folder)->where('type',$type)->groupBy('filename')->pluck('filename');
    }
    /**
     * @param $folder￿
     * @param $type
     * @return array
     */
    public function getQualityList($folder,$type){
        $output = $this->where('folder',$folder)->where('type',$type)->first();
        $config =  json_decode( $output->config,true);
        $field = $type == 'model'?"model_cutface_list":"texture_resize_list";
        $output = isset($config[$field])? explode(',',$config[$field]): [];
        array_push( $output, 'original');
        return $output;
    }
    /**
     * @param $fileName
     * @param $ext
     * @param $folder
     * @return mixed
     */
    public function checkRepeat($fileName, $ext, $folder)
    {
        $admin = new Admin;
        return $this->where('user_id',$admin -> userId())
            ->where('folder', $folder)
            ->where('filename', str_replace('.'.$ext,'', $fileName))
            ->where('suffix',Str::lower($ext))
            ->first();
    }
    public function download($sign)
    {
        $material = $this->where('sign',$sign)->first();
        if($material){
            $files  = getRealPath($material->path);
            $name = $material->filename.'.'.$material->suffix;
            return response()->download($files, $name ,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
        }
    }
}
