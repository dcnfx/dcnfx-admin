<?php
/**
 * 素材管理
 *
 * @author      dcnfx
 * @Time: 2019/07/14 15:57
 * @version     1.0 版本号
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreRequest;
use App\Models\Material;
use App\Models\Admin;
use App\Service\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Log;
use App\Jobs\ImportModel;
use Illuminate\Support\Str;

class MaterialController extends BaseController
{
    public function index(Request $request){
        $admin = new Admin;
        $directories = $admin -> getProjectFolder();
        $material = Material::query();
        if($request->filled('folder')) {
            $material->where('folder',$request->input('folder'));
        }
        if($request->filled('type')) {
            $material->where('type',$request->input('type'));
        }
        if($request->filled('title')) {
            $material->where('filename', 'LIKE', '%'.trim($request->input('title')).'%');
        }
        if($request->filled('begin')) {
            $material->where('created_at', '>=', strtotime($request->input('begin')));
        }
        $data = $material->latest('id')->paginate(50);
        return view('material.list',['list'=>$data,'input'=>$request->all(),'project_list'=>$directories]);
    }
    public function upload(){
        $admin = new Admin;
        $directories = $admin -> getProjectFolder();
        return view('material.upload',['project_list'=>$directories]);
    }
    public function store(StoreRequest $request){
        //上传文件最大大小,单位M
        $maxSize = config('admin.maxUploadSize');
        //支持的上传文件类型
        $allowed_model_extensions = explode(',', config('admin.allowed_model_extensions'));
        $allowed_texture_extensions =  explode(',', config('admin.allowed_texture_extensions'));
        $uncompress_model_extensions =  explode(',', config('admin.uncompress_model_extensions'));
        $allowed_others_extensions = ['mtl'];
        $allowed_extensions = array_merge($allowed_model_extensions, $allowed_texture_extensions, $allowed_others_extensions);
        //返回信息json
        $data = ['code'=> -1, 'msg'=>'上传失败', 'data'=>''];
        $file = $request->file('file');
        $folder =  $request->input('project_name');
        $admin = new Admin;
        $material = new Material;

        //检查文件是否上传完成
        if ($file->isValid()){
            //检测文件类型
            $ext = $file->getClientOriginalExtension();
            if (!in_array(Str::lower($ext),$allowed_extensions)){
                $data['msg'] = "请上传".implode(",",$allowed_extensions)."格式的文件";
                return response()->json($data);
            }
            //检测文件大小
            if ($file->getSize() > $maxSize*1024*1024){
                $data['msg'] = "每个文件大小限制".$maxSize."MB";
                return response()->json($data);
            }
        }else{
            $data['msg'] = $file->getErrorMessage();
            return response()->json($data);
        }
        //检查文件是否在该项目下重复
        $originalName = $file->getClientOriginalName();
        $checkRepeat =  $material -> checkRepeat($originalName, $ext, $folder);
        if($checkRepeat){
            $data['msg'] = "该文件已存在于项目中，请删除后再上传。";
            return response()->json($data);
        }
        $newFile = Str::random(32).".".Str::lower($ext);
        $path = $file->storeAs($admin -> userId().'/'.$folder, $newFile);//路径为用户id/项目名
        if($path){
            //$inputData = $request->all();
            $sign = Str::random(32);
            $inputData = [
                'user_id'  => $admin -> userId(),
                'folder'   => $folder,
                'filename' => str_replace('.'.$ext,'', $originalName),
                'suffix'   => Str::lower($ext),
                'path'     => $path,
                'size'     => $file->getSize(),
                'desc'     => 'original',
                'sign'     => $sign,
            ];
            if (in_array(Str::lower($ext), $allowed_texture_extensions)){
                $inputData['type'] = 'texture';
                $inputData['config'] = json_encode($request->only(['is_resize_image', 'texture_compress','texture_resize_list']));
                $compress_list = $request->input('is_resize_image')== "on"  ? $request->input('texture_resize_list'): "unprocessed";
            }else if(in_array(Str::lower($ext), $allowed_model_extensions)){
                $inputData['type'] = 'model';
                $inputData['config'] = json_encode($request->only(['is_cutface', 'model_compress','model_cutface_list']));
                $compress_list = $request->input('is_cutface')== "on" ? $request->input('model_cutface_list'):"unprocessed";
            }else if(in_array(Str::lower($ext), $allowed_others_extensions)){
                $inputData['type'] = 'other';
                $inputData['config'] = '{}';
                $compress_list ='unprocessed';
            }else{
                Log::addLogs(trans('fzs.material.upload_fail').$inputData['filename'],'/material/upload');
                $data['msg'] = "请上传".implode(",",$allowed_extensions)."格式的文件";
                return response()->json($data);
            }
            $compress_list = in_array(Str::lower($ext), $uncompress_model_extensions)?'unprocessed':$compress_list;

            $res = $material->checkStore($inputData);
            if($res){
                Log::addLogs(trans('fzs.material.upload_success').$inputData['filename'],'/material/upload');
                $data = [
                    'code'  => 0,
                    'msg'   => '上传成功',
                    'compress_list'  => $compress_list,
                    'url'   => Storage::url($path)
                ];
            }else {
                Log::addLogs(trans('fzs.material.upload_fail').$inputData['filename'],'/material/upload');
                $data['msg'] = '数据库写入失败';
                Storage::delete($path);//删除上传的文件
            }
        }else{
            $data['msg'] = "文件写入失败";
        }
        $this->dispatch(new ImportModel($sign));
        return response()->json($data);
    }
    /**
     * 素材删除
     */
    public function destroy($id)
    {
        $model = new Material();
        $material = DataService::handleDate($model,['id'=>$id],'materials-delete');
        if($material['status']==1)Log::addLogs(trans('fzs.material.del_material').trans('fzs.common.success'),'/materials/destroy/'.$id);
        else Log::addLogs(trans('fzs.material.del_material').trans('fzs.common.fail'),'/menus/destroy/'.$id);
        return $material;
    }

    public function file(){
        $admin = new Admin;
        $directories = $admin -> getProjectFolder();
        return view('material.file',['project_list'=>$directories]);
    }
    public function download($sign){
        $material = new Material;
        return $material->download($sign);

    }
}
