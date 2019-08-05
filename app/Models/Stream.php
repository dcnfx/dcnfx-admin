<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class Stream extends BaseModel
{
    protected $table = 'admin_streams';
    protected $fillable = ['title','folder','type','url','frame','cam_to_world','intrinsics','status','camera_json','model_list','keycode'];
}