<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Log;
use App\Models\Material;
use App\Models\Project;
use App\Models\Stream;
use App\Service\DataService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ProjectController extends BaseController
{
    public function index(){
        return view('project.list',['list'=> Project::get()]);
    }

    public function update(Request $request)
    {

    }
    public function file($id){
        $project = Project::find($id);
        $config =  json_decode($project->filelist,true);
        $folder = $project->folder;
        $modelList = $config['file'];
        $modelQuality = $config['model_quality'];
        $textureQuality = $config['texture_quality'];
        $data=[];
        $out = ['code'=> -1, 'msg'=>'数据获取中', 'data'=>$data];
        foreach ($modelList as $modelFile){
            $model = Material::where('folder',$folder)->where('type','model')->where('filename',$modelFile)->first();
            foreach (Material::where('folder',$folder)->where('type','texture')->get() as $item) {
                if(Str::contains($item->filename,$model->filename)){
                    $compressModel = $model->compressed->where('desc', $modelQuality)->first();
                    $compressTexture = $item->compressed->where('desc',$textureQuality)->first();
                    if( $compressModel &&  $compressTexture){
                        $data[] = array(
                            'index' =>  $compressModel->filename,
                            'model' =>  ['url'=>asset('storage/'.$compressModel->path),'local'=>'static/'.$compressModel->path],
                            'texture' => [['url'=>asset('storage/'.$compressTexture->path),'local'=>'static/'.$compressTexture->path]],
                        );
                    }
                    else{
                        $out['msg'] = "你请求的模型或贴图不存在";
                        return response()->json($out)->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                    }
                }
            }
        }
        return response()->json(['code'=>0,'msg'=>"获取成功",'data'=>$data])->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }


    public function scene($id)
    {
        $project = Project::find($id);
        $folder = $project->folder;
        $data = [];
        $style= ["position" => "fixed",'top'=>'0','left'=>'0'];
        $data["camera"] = array(
            "fov" => 45,
            "position" => ["x" => 400, "y" => 400, "z" => 400],
            "lookAt" => ["x" => 0, "y" => 0, "z" => 0],
            "near" => 0.1,
            "far" => 40000
        );
        $data["renderer"] = array(
            "antialias" => true,
            "alpha" => true,
            "preserveDrawingBuffer" => true,
            "style" =>  $style
        );
        $data["controls"] = ["autoRotate" => false];
        $data["isGui"] = false;
        return response()->json(['code'=>0,'msg'=>"获取成功",'data'=>$data])->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }


    public function fusion($id){
        $project = Project::find($id);
        $streamList = Stream::find(json_decode($project->streamlist));
        $data = [];
        foreach ($streamList as $stream){
            if($stream->type == 'online'){
                $streamType = 'flv';
            } else{
                $streamType = 'mp4';
            }
            if($stream->status==1){
                $data[$stream->folder.$stream->id] = array(
                    'index' =>  $stream->folder.$stream->id,
                    'keyCode' => $stream->keycode,
                    'camJson' => json_decode($stream->camera_json,true) ,
                    'flv' => ['type' => $streamType,"url"=> $stream->url],
                    'modelIndex' => json_decode($stream->model_list,true),
                    'isGlobalView' => true
                );
            }

        }
        return response()->json(['code'=>0,'msg'=>"获取成功",'data'=>$data])->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }



    public function store(Request $request){
        $model = new Project();
        $project= DataService::handleDate($model,$request->all(),'projects-add_or_update');
        if($project['status']==1)Log::addLogs(trans('fzs.menus.handle_menu').trans('fzs.common.success'),'/menus/store');
        else Log::addLogs(trans('fzs.menus.handle_menu').trans('fzs.common.fail'),'/project/store');
        return $project;
    }
    public function show($id){
        $model = Project::find($id);
        return view('project.show',['model'=> $model]);
    }

    public function edit($id = 0)
    {
        $admin = new Admin;
        $material = new Material;
        $folders = $admin -> getProjectFolder();
        $project = $id ? Project::find($id):[];
        $file_list = [];
        $stream_list = [];
        if($id > 0){
            $allmodel = $material -> getModelList( $project -> folder,'model');
            $mymodel = json_decode( $project -> filelist,true)['file'];
            $mystream = json_decode( $project -> streamlist,true);
            $stream = Stream::where('folder',$project->folder)->get();
            $id_temp = 0;
            foreach ($allmodel as $item){
                $id_temp ++;  //防止替换文件，改变id，数据库里不保存ID，故此处id随意了，没取原id
                $file_list[] = ['title' => $project->folder.'|'.$item,'id'=> $id_temp,'checked'=>in_array($item,$mymodel)];
            }
            foreach ($stream as $item){
                $stream_list[] = ['id'=> $item->id, 'title' => $item->title, 'checked'=>in_array($item->id,$mystream),'disabled'=>$item->status==0];
            }
            $project['model_quality_list'] = $material -> getQualityList($project->folder,'model');
            $project['texture_quality_list'] = $material -> getQualityList($project->folder,'texture');
        }
        return view('project.edit',['id' => $id, 'project' => $project,'folders'=>$folders,'file_list'=>$file_list,'stream_list'=>$stream_list]);
    }
    public function data(Request $request)
    {
        $project = $request->input('folder');
        $id = $request->input('id');
        $out = ['code'=> -1, 'msg'=>'数据获取失败', 'data'=>[],'fusion'=>[]];
        $streams = Stream::where('folder',$project)->get();
        $materials_model = Material::where('folder',$project)->where('type','model')->get();
        $materials_texture = Material::where('folder',$project)->where('type','texture')->get();
        if ($streams ->isEmpty()){
            $out['msg'] = "你的监控不存在";
            $out['fusion']=[];
        }
        if ($id>0){

        } else {
            foreach ($streams as $stream){
                $out['fusion'][] = ['title' => $stream->title,'id'=> $stream->id,'checked'=>true,'disabled'=>$stream->status==0];
            }
            foreach ($materials_model as $model){
                foreach ( $materials_texture as $texture){
                    if(Str::contains($texture->filename,$model->filename) ){
                        $out['data'][] = ['title' => $model->folder.'|'.$model->filename.'|'.$model->suffix.'|'.$texture->suffix,'id'=> $model->id,'checked'=>true];
                        $config1 =  json_decode($texture->config,true);
                        $out['texture_quality'] = isset($config1["texture_resize_list"]) ? explode(',',$config1["texture_resize_list"]): [];
                    }
                }
                $config2 =  json_decode($model->config,true);
                $out['model_quality'] = isset($config2["model_cutface_list"])? explode(',',$config2["model_cutface_list"]) : [];
            }
        }
        $out['msg'] = "获取成功";
        $out['code'] = 0;
        return response()->json($out)->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
