<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class MaterialCompress extends BaseModel
{
    protected $table = 'admin_materials_compress';
    protected $fillable = ['material_id','filename','suffix','type','path','size','desc','status'];


}

