<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    function index(){
        return view('home.index',['title'=>"徐家汇"]);
    }
}
