@section('title', '监控编辑')
@section('content')
        <div class="layui-form-item">
            <label for="" class="layui-form-label">项目标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" value="{{ $project['title']??'' }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">背景图片：</label>
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
        <div class="layui-form-item">
            <label class="layui-form-label">文件路径：</label>
            <div class="layui-input-block">
                <select name="folder" lay-filter="folder" lay-verify="required">
                    <option value="{{$project['folder'] ?? ''}}">{{ $project['folder'] ?? '请选择一个文件路径' }} </option>
                    @if(!isset($project['folder'] ))
                    @foreach($folders as $item)
                        <option value="{{$item}}">{{$item}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">模型文件选择：</label>
            <div class="layui-input-block">
                <div id="tree_model" class="demo-tree-box"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">模型质量选择：</label>
            <div class="layui-input-block" id="model_quality">
                @if(isset($project->filelist))
                    @foreach($project['model_quality_list'] as $item)
                    <input type='radio' name='model_quality' value='{{$item}}' title='{{$item}}'
                            {{json_decode($project->filelist,true)["model_quality"]==$item? 'checked':''}}>
                    @endforeach
                @else
                <div class="layui-form-mid layui-word-aux">请先选择路径</div>
                @endif
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">贴图质量选择：</label>
            <div class="layui-input-block" id="texture_quality">
                @if(isset($project->filelist))
                    @foreach($project['texture_quality_list'] as $item)
                        <input type='radio' name='texture_quality' value='{{$item}}' title='{{$item}}'
                                {{json_decode($project->filelist,true)["texture_quality"]==$item? 'checked':''}}>
                    @endforeach
                @else
                <div class="layui-form-mid layui-word-aux">请先选择路径</div>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">监控选择选择：</label>
            <div class="layui-input-block">
                <div id="tree_stream" class="demo-tree-box"></div>
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
            var inst1 = tree.render({
                elem: '#tree_model'  //绑定元素
                ,data:{!! json_encode($file_list) !!}
                ,showCheckbox: true  //是否显示复选框
                ,id: 'demoId1'
                ,text: {
                    defaultNodeName: '未命名' //节点默认名称
                    ,none: '无数据,请先选择项目路径' //数据为空时的提示文本
                }
            });

            var inst2 = tree.render({
                elem: '#tree_stream'  //绑定元素
                ,data: {!! json_encode($stream_list) !!}
                ,showCheckbox: true  //是否显示复选框
                ,id: 'demoId2'
                ,text: {
                    defaultNodeName: '未命名' //节点默认名称
                    ,none: '无数据,请先去添加监控点' //数据为空时的提示文本
                }
            });
            form.on('select(folder)', function(data){
                $.ajax({
                    url:"{{route('admin.project.data')}}",
                    data:{"folder":data.value,"id":$("input[name='id']").val(),"_token":"{{ csrf_token() }}"},
                    type:'post',
                    dataType:'json',
                    success:function(res){
                        if(res.code == 0){
                            //渲染
                            var inst1 = tree.render({
                                elem: '#tree_model'  //绑定元素
                                ,data: res.data
                                ,showCheckbox: true  //是否显示复选框
                                ,id: 'demoId1'
                                ,text: {
                                    defaultNodeName: '未命名' //节点默认名称
                                    ,none: '无数据,请先选择项目路径' //数据为空时的提示文本
                                }
                            });

                            var inst2 = tree.render({
                                elem: '#tree_stream'  //绑定元素
                                ,data: res.fusion
                                ,showCheckbox: true  //是否显示复选框
                                ,id: 'demoId2'
                                ,text: {
                                    defaultNodeName: '未命名' //节点默认名称
                                    ,none: '无数据,请先去添加监控点' //数据为空时的提示文本
                                }
                            });
                            var model_quality = res.model_quality;
                            var texture_quality = res.texture_quality;
                            var html_model_quality = '';
                            var html_texture_quality = '';
                            layui.each(model_quality, function(index, item){
                                html_model_quality += "<input type='radio' name='model_quality' value='"+item+"' title='"+item+"'>";
                            });
                            layui.each(texture_quality, function(index, item){
                                html_texture_quality += "<input type='radio' name='texture_quality' value='"+item+"' title='"+item+"'>";
                            });
                            html_model_quality += "<input type='radio' name='model_quality' value='original' title='original' checked>";
                            html_texture_quality += "<input type='radio' name='texture_quality' value='original' title='original' checked>";
                            $('#model_quality').html(html_model_quality);
                            $('#texture_quality').html(html_texture_quality);
                            $('#model_quality .layui-word-aux').hide();
                            $('#texture_quality .layui-word-aux').hide();
                            form.render('radio');
                        }else{
                            layer.msg(res.msg,{shift: 6,icon:5});
                        }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        layer.msg('网络失败', {time: 1000});
                    }
                });
            });

            form.on('submit(formDemo)', function(data) {
                data.field.model = tree.getChecked('demoId1');
                data.field.stream = tree.getChecked('demoId2');
                console.log(data.field);
                $.ajax({
                    url:"{{route('project.store')}}",
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
