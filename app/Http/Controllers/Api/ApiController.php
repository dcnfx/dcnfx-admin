<?php

namespace App\Http\Controllers\Api;

use App\Models\Material;
use App\Models\Project;
use App\Models\Stream;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * 模型和贴图文件加载API
     * @param $project
     * @param $type1
     * @param $type2
     * @return \Illuminate\Http\JsonResponse
     */
    public function file($project,$type1,$type2){
        $out = ['code'=>0,'msg'=>"获取成功"];
        $model = new  Material();
        $projectModel = new Project();
        $material =  $model -> where('folder',$project)->get();
        if($material->isEmpty()){
            $out = ['code'=> -1, 'msg'=>'你的项目不存在'];
        } else{
            $modelIndex =  $model -> getModelList( $project,'model');
            $res = $projectModel -> getProjectFile($modelIndex ,$project,$type1,$type2,$project=='xujiahui');
            if($res){
                $out['data'] = $res;
            } else{
                $out = ['code'=> -1, 'msg'=>'数据获取失败，请检查参数是否正确'];
            }
        }
        return response()->json($out)->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function scene($project)
    {
        $data = [];
        $out = ['code' => -1, 'msg' => '数据获取中', 'data' => $data];
        $material = Material::where('folder',$project)->get();
        if($material->isEmpty()){
            $out['msg'] = "你的项目不存在";
            return response()->json($out)->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }
        if($project=="xujiahui"){
            $style= ["display" => "block"];
        }
        else{
            $style= ["position" => "fixed",'top'=>'0','left'=>'0'];
        }
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

    public function fusion($project){
        $data = [];
        $out = ['code' => -1, 'msg' => '数据获取中', 'data' => $data];
        $streams =  Stream::where('folder',$project)->get();
        if($streams->isEmpty()){
            $out['msg'] = "你的项目不存在";
            return response()->json($out)->setEncodingOptions(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }
        foreach ( $streams as $stream){
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
}
