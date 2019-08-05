<?php

namespace App\Models;

use App\Models\Base\BaseModel;
class Project extends BaseModel
{
    protected $table = 'admin_projects';
    protected $fillable = ['title','folder','background','logo','streamlist','filelist'];

}
