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
                <option value="{{$info['folder'] ?? ''}}">{{ $info['folder'] ?? '请选择一个项目路径' }} </option>
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
                   @elseif(isset($info['type'])&&$info['type']=='offline')
                       checked
                    @endif>
            <input type="radio" name="type" value="online" lay-filter="stream-type"  autocomplete="off" title="实时流" {{isset($info['type'])&&$info['type']=='online'?'checked':''}}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">监控地址：</label>
        <div class="layui-input-block">
            <input name="url" lay-verify="required" id="stream-url" value="{{$info['url']?? ''}}" autocomplete="off" placeholder="监控地址"  class="layui-input">
        </div>

        <div class="layui-input-block layui-upload offline" style="width: auto;  @if(isset($info['type'])&&$info['type']=='online') display:none @endif">
            <button type="button" class="layui-btn layui-btn-primary"  style="margin-top: 10px" id="stream-upload">
                <i class="layui-icon layui-icon-upload"></i>上传视频
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">监控封面：</label>
        <div class="layui-input-block">
            <input name="frame" lay-verify="required" id="frame-upload-normal-img" autocomplete="off" placeholder="图片地址" value="{{$info['frame']?? 'http://www.placehold.it/800x600/EFEFEF/AAAAAA&text='.date('Y-m-d')}}" class="layui-input">
        </div>
        <div class="layui-input-block" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary" style="margin-top: 10px"  id="frame-upload">
                <i class="layui-icon layui-icon-upload"></i>上传图片
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">选择热键：</label>
        <div class="layui-input-block">
            <input type="hidden" name="key_code" value="{{$info['keycode'] ?? 'null'}}" required lay-verify="required" placeholder="请选择热荐" autocomplete="off" class="layui-input" readonly>
            <div id="key_code_list" style="margin-top: 4px;"></div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">相机坐标：cam_to_world</label>
        <div class="layui-input-block">
            <textarea name="cam_to_world" id="cam_to_world" required lay-verify="required" placeholder="请输入相机世界坐标" class="layui-textarea">@if(isset($info['cam_to_world'])){!! $info['cam_to_world'] !!}@endif</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">相机内参：intrinsics</label>
        <div class="layui-input-block">
            <textarea name="intrinsics" id="intrinsics" required lay-verify="required" placeholder="请输入相机内参" class="layui-textarea">@if(isset($info['intrinsics'])){!! $info['intrinsics'] !!} @endif</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">包含的模型：临时方案</label>
        <div class="layui-input-block">
            <textarea name="model_list"  placeholder="请输入模型列表，临时先向黄华要" class="layui-textarea">@if(isset($info['model_list'])){!! $info['model_list'] !!} @endif</textarea>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">备注：</label>
        <div class="layui-input-block">
            <input name="remarks"  id="remarks" placeholder="请输入备注" value="{{$info['remarks'] ?? ''}}" class="layui-input">
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
            var data = [['无','null'], ['1','49'],['2','50'],['3','51'],['4','52'],['5','53'],['6','54'],['7','55'],['8','56'],['9','57']];
            var html = '';
            layui.each(data, function(index, item){
                var iconclass = '';
                if($("input[name='key_code']").val()==item[1])
                    iconclass = 'layui-btn-warm';
                    html+='<div class="layui-btn layui-btn-primary layui-btn-sm fzs-icon '+iconclass+'" data-icon="'+item[1]+'" style="margin-left:0;margin-right:10px;margin-top: 3px;" onclick="chose_icon(this)" title="'+item[0]+'">'+item[0] +'</div>';
            });
            $('#key_code_list').html(html);
            var layer = layui.layer;
            form.on('radio(stream-type)', function(data){
               if(data.value == "online"){
                   $('.offline').hide();
               } else{
                   $('.offline').show();
               }
            });
            form.on('submit(formDemo)', function(data) {
                var camera_json={};
                camera_json.fov = tranFov(JSON.parse($('#intrinsics').val().toString()));
                var tranPosRes = tranPos(JSON.parse($('#cam_to_world').val().toString()));
                camera_json.position = tranPosRes.position;
                camera_json.poseMat4 = tranPosRes.poseMat4;
                camera_json.lookAt = $0(camera_json);
                data.field.camera_json = JSON.stringify(camera_json);
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
                ,url: '{{route('admin.upload')}}'
                ,accept: 'file'
                ,size: 1024 * 300  //300MB
                ,exts: 'mp4'
                ,data:{"_token":"{{ csrf_token() }}"}
                ,before: function(obj){
                    layer.load(2);
                }
                ,done: function(res){
                    //如果上传失败
                    layer.closeAll('loading'); //关闭loading
                    if(res.code < 0){
                        return layer.msg(res.msg);
                    } else {
                        $('#stream-url').val(res.url);
                    }
                }
                ,error: function(){
                    //演示失败状态，并实现重传
                    layer.closeAll('loading'); //关闭loading
                    var demoText = $('#test-upload-demoText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function(){
                        uploadInst.upload();
                    });
                }
            });


            var uploadThumb = upload.render({
                elem: '#frame-upload'
                ,url: '{{route('admin.upload')}}'
                ,accept: 'file'
                ,size: 1024 * 3  //3MB
                ,exts: 'jpg'
                ,data:{"_token":"{{ csrf_token() }}"}
                ,before: function(obj){
                    layer.load(2);
                    //预读本地文件示例，不支持ie8
                    // obj.preview(function(index, file, result){
                    //     $('#stream-upload-normal-img').attr('src', result); //图片链接（base64）
                    // });
                }
                ,done: function(res){
                    //如果上传失败
                    layer.closeAll('loading'); //关闭loading
                    if(res.code < 0){
                        return layer.msg(res.msg);
                    } else {
                        $('#frame-upload-normal-img').val(res.url);
                    }
                }
                ,error: function(){
                    //演示失败状态，并实现重传
                    layer.closeAll('loading'); //关闭loading
                    var demoText = $('#test-upload-demoText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function(){
                        uploadInst.upload();
                    });
                }
            });

        });
        function tranPos(pose) {
            let position, poseMat4 = [];
            var poseAS = pose;
            position = {
                x: parseFloat(poseAS[0][3]),
                y: parseFloat(poseAS[1][3]),
                z: parseFloat(poseAS[2][3])
            };
            poseMat4[0] = poseAS[0][0];
            poseMat4[1] = poseAS[0][1];
            poseMat4[2] = poseAS[0][2];
            poseMat4[3] = poseAS[0][3];
            poseMat4[4] = poseAS[1][0];
            poseMat4[5] = poseAS[1][1];
            poseMat4[6] = poseAS[1][2];
            poseMat4[7] = poseAS[1][3];
            poseMat4[8] = poseAS[2][0];
            poseMat4[9] = poseAS[2][1];
            poseMat4[10] = poseAS[2][2];
            poseMat4[11] = poseAS[2][3];
            poseMat4[12] = 0.0;
            poseMat4[13] = 0.0;
            poseMat4[14] = 0.0;
            poseMat4[15] = 1.0;
            return {
                position: position,
                poseMat4: poseMat4
            }
        }
        function tranFov(intrinsic) {
            return 2 * Math.atan(parseFloat(intrinsic[1][2]) / parseFloat(intrinsic[1][1])) * 180 / Math.PI
        }
        function chose_icon(obj){
            $("input[name='key_code']").val($(obj).attr('data-icon'));
            if($(obj).hasClass('layui-btn-warm'))$(obj).removeClass('layui-btn-warm');
            else {
                var icons = $('.fzs-icon');
                icons.each(function(index, item) {
                    $(item).removeClass('layui-btn-warm');
                });
                $(obj).addClass('layui-btn-warm');
            }
        }
    </script>
@endsection
@extends('common.edit')