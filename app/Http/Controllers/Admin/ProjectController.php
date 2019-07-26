<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends BaseController
{
    public function index(){

        return view('project.list',['list'=> Project::get()->toArray()]);
    }

    public function update(Request $request)
    {

    }
    public function edit( $id = 0 )
    {
        $title = $id ? "修改项目" : "新建项目";
        $project = $id ? Project::find($id):[];
        return view('project.edit',['id' => $id, 'title' => $title,'project' => $project]);
    }
}
