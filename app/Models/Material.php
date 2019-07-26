<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class Material extends BaseModel
{
    protected $table = 'admin_materials';
    protected $fillable = ['user_id','folder','filename','suffix','sign','type','path','size','desc','config'];
    public function compressed(){
        return $this->hasMany(MaterialCompress::class,'material_id','id' );
    }

}
