@section('title', '监控编辑')
@section('content')
    <div class="layui-form-item">
        <label class="layui-form-label">监控名称：</label>
        <div class="layui-input-block">
            <input type="text" value="{{$info['title']?? ''}}" name="title" required lay-verify="required" placeholder="输入监控名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属项目：</label>
        <div class="layui-input-block">
            <select name="folder">
                <option value="">{{ $info['folder'] ?? '请选择一个项目路径' }} </option>
                @foreach($project_list as $item)
                    <option value="{{$item}}">{{$item}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">监控类型：</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="offline" lay-filter="stream-type" title="本地流" autocomplete="off"
                   @if(!isset($info['type']))
                       checked
                   @elseif(isset($info['type'])&&$info['type'])
                       checked
                    @endif>
            <input type="radio" name="type" value="online" lay-filter="stream-type"  autocomplete="off" title="实时流" {{isset($info['type'])&&!$info['type']?'checked':''}}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">监控地址：</label>
        <div class="layui-input-block">
            <input name="url" lay-verify="required" id="stream-url" autocomplete="off" placeholder="监控地址" value="" class="layui-input">
        </div>
        <div class="layui-input-block layui-upload offline" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary"  style="margin-top: 10px" id="stream-upload">
                <i class="layui-icon layui-icon-upload"></i>上传视频
            </button>
        </div>

    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">监控封面：</label>
        <div class="layui-input-block">
            <input name="frame" lay-verify="required" id="frame-upload-normal-img" autocomplete="off" placeholder="图片地址" value="http://www.placehold.it/800x600/EFEFEF/AAAAAA&text={{ $info['folder'] ?? date("Y-m-d") }}" class="layui-input">
        </div>
        <div class="layui-input-block" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary" style="margin-top: 10px"  id="frame-upload">
                <i class="layui-icon layui-icon-upload"></i>上传图片
            </button>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">相机坐标：</label>
        <div class="layui-input-block">
            <textarea name="cam_to_world" required lay-verify="required" placeholder="请输入相机世界坐标" class="layui-textarea"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">相机内参：</label>
        <div class="layui-input-block">
            <textarea name="intrinsics" required lay-verify="required" placeholder="请输入相机内参" class="layui-textarea"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">备注：</label>
        <div class="layui-input-block">
            <input name="remarks"  placeholder="请输入备注" value="" class="layui-input">
        </div>
    </div>

@endsection
@section('id',$id)
@section('js')
    <script>
        layui.use(['form','jquery','laypage', 'layer','upload'], function() {
            var form = layui.form,
                $ = layui.jquery
                ,upload = layui.upload;
            form.render();
            var layer = layui.layer;

            form.on('radio(stream-type)', function(data){
               if(data.value == "online"){
                   $('.offline').hide();
               }
               else{
                   $('.offline').show();
               }
            });


            form.on('submit(formDemo)', function(data) {
                $.ajax({
                    url:"{{route('stream.store')}}",
                    data:data.field,
                    type:'post',
                    dataType:'json',
                    success:function(res){
                        if(res.status == 1){
                            layer.msg(res.msg,{icon:6});
                            var index = parent.layer.getFrameIndex(window.name);
                            setTimeout('parent.layer.close('+index+')',2000);
                        }else{
                            layer.msg(res.msg,{shift: 6,icon:5});
                        }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        layer.msg('网络失败', {time: 1000});
                    }
                });
                return false;
            });

            var uploadInst = upload.render({
                elem: '#stream-upload'
                ,url: '/upload/'
                ,before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#stream-upload-normal-img').attr('src', result); //图片链接（base64）
                    });
                }
                ,done: function(res){
                    //如果上传失败
                    if(res.code > 0){
                        return layer.msg('上传失败');
                    }
                    //上传成功
                }
                ,error: function(){
                    //演示失败状态，并实现重传
                    var demoText = $('#test-upload-demoText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function(){
                        uploadInst.upload();
                    });
                }
            });

        });
    </script>
@endsection
@extends('common.edit')