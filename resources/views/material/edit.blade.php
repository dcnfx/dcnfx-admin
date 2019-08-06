@section('title', '素材编辑')
@section('content')
        <div class="layui-form-item">
            <label for="" class="layui-form-label">文件名</label>
            <div class="layui-input-block">
                <input type="text" name="filename" value="{{ $info['filename']??'' }}" lay-verify="required" placeholder="请输入文件名" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">重新上传文件：</label>
            <div class="layui-input-block">
                <input name="background" lay-verify="required" id="background" autocomplete="off" placeholder="图片地址" value="{{$project['background']?? 'https://fusion.dgene.com/storage/1/media/CcHpSbmo10OG6WgLdEQ06hIw4fvpPYb9.jpg'}}" class="layui-input">
            </div>
            <div class="layui-upload layui-input-block">
                <button type="button" class="layui-btn" id="bg-upload">上传图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" style="max-height: 200px" src="{{$project['background']?? 'https://fusion.dgene.com/storage/1/media/CcHpSbmo10OG6WgLdEQ06hIw4fvpPYb9.jpg'}}" id="bg-upload-normal-img">
                    <p id="test-upload-demoText"></p>
                </div>
            </div>
        </div>
@endsection
@section('id',$id)
@section('js')
    <script>
        layui.use(['form', 'transfer','jquery', 'layer','upload','element','tree'], function() {
            var $ = layui.jquery
                ,transfer = layui.transfer
                ,form = layui.form
                ,upload = layui.upload
                ,layer = layui.layer
                ,element = layui.element
                ,tree = layui.tree;
            var uploadInst = upload.render({
                elem: '#bg-upload'
                ,url: '{{route('admin.upload')}}'
                ,exts: 'jpg'
                ,data:{"_token":"{{ csrf_token() }}"}
                ,before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#bg-upload-normal-img').attr('src', result); //图片链接（base64）
                    });
                }
                ,done: function(res){
                    //如果上传失败
                    if(res.code > 0){
                        return layer.msg('上传失败');
                    }
                    else {
                        $('#background').val(res.url);
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



            form.on('submit(formDemo)', function(data) {
                $.ajax({
                    url:"{{route('material.store')}}",
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
        });
    </script>
@endsection
@extends('common.edit')
