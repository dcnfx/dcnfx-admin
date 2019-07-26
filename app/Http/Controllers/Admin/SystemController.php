<?php

namespace App\Http\Controllers\Admin;

use App\Models\System;
use Illuminate\Http\Request;

class SystemController extends BaseController
{
    public function index(){
        $config = System::pluck('value','key');
        return view('system.index',compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token','_method']);
        if (empty($data)){
            return back()->withErrors(['status'=>'无数据更新']);
        }
       System::truncate();
        foreach ($data as $key=>$val){
            System::create([
                'key' => $key,
                'value' => $val
            ]);
        }
        return back()->with(['status'=>'更新成功']);
    }
}
