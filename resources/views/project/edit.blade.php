@section('title', '{{$title}}')
@section('header')
    <h2>{{$title}}</h2>
@endsection
@section('form')
    <form class="layui-form" wid100 action="{{route('project.store')}}" method="post">
        {{csrf_field()}}
        <input name="id" type="hidden" value="{{$id}}">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">项目标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" value="{{ $project['title']??'' }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">背景图片</label>
            <div class="layui-upload layui-input-block">
                <button type="button" class="layui-btn" id="bg-upload">上传图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" style="max-height: 200px" id="bg-upload-normal-img">
                    <p id="test-upload-demoText"></p>
                </div>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">模型文件选择</label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="model_select">选择文件</button>
                <div id="user_list" data-id=""></div>

            </div>
        </div>




        <div class="layui-form-item">
            <label for="" class="layui-form-label">模型文件选择</label>
            <div class="layui-input-block">
                <div id="test1"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确认保存</button>
            </div>
        </div>
    </form>
@endsection
@section('js')
    <script>
        layui.use(['form', 'transfer','jquery', 'layer','upload','element'], function() {
            var $ = layui.jquery
                ,transfer = layui.transfer
                ,form = layui.form
                ,upload = layui.upload
                ,layer = layui.layer
                ,element = layui.element;

            var uploadInst = upload.render({
                elem: '#bg-upload'
                ,url: '/upload/'
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
            layui.data('tabData',{key:'id',value:[]});
            $('#model_select').on('click',function () {
                layer.open({
                    type: 2,
                    title: '选择文件',
                    area: ['90%', '90%'],
                    content: '{{route('admin.materials.file')}}',
                    end: function () {
                        var sumit = layui.data('tabData').sumit;
                        if(sumit==0){
                            //用户点了保存按钮
                            //读取缓存
                            var id = layui.data('tabData').id;
                            $('#user_list').text(JSON.stringify(id))
                            $('#user_list').data('id',JSON.stringify(id))
                        }else{
                            //用户没有保存关闭窗口
                            var id = $('#user_list').data('id')
                            if(id===''){
                                layui.data('tabData',{key:'id',value:[]});
                            }else{
                                layui.data('tabData',{key:'id',value:JSON.parse(id)});
                            }
                        }
                    }
                });
            })



            //渲染
            transfer.render({
                elem: '#test1'  //绑定元素
                ,data: data2 = [
                    {"value": "1", "title": "瓦罐汤"}
                    ,{"value": "2", "title": "油酥饼"}
                    ,{"value": "3", "title": "炸酱面"}
                    ,{"value": "4", "title": "串串香", "disabled": true}
                    ,{"value": "5", "title": "豆腐脑"}
                    ,{"value": "6", "title": "驴打滚"}
                    ,{"value": "7", "title": "北京烤鸭"}
                    ,{"value": "8", "title": "烤冷面"}
                    ,{"value": "9", "title": "毛血旺", "disabled": true}
                    ,{"value": "10", "title": "肉夹馍"}
                    ,{"value": "11", "title": "臊子面"}
                    ,{"value": "12", "title": "凉皮"}
                    ,{"value": "13", "title": "羊肉泡馍"}
                    ,{"value": "14", "title": "冰糖葫芦", "disabled": true}
                    ,{"value": "15", "title": "狼牙土豆"}
                    ,{"value": "1", "title": "瓦罐汤"}
                    ,{"value": "2", "title": "油酥饼"}
                    ,{"value": "3", "title": "炸酱面"}
                    ,{"value": "4", "title": "串串香", "disabled": true}
                    ,{"value": "5", "title": "豆腐脑"}
                    ,{"value": "6", "title": "驴打滚"}
                    ,{"value": "7", "title": "北京烤鸭"}
                    ,{"value": "8", "title": "烤冷面"}
                    ,{"value": "9", "title": "毛血旺", "disabled": true}
                    ,{"value": "10", "title": "肉夹馍"}
                    ,{"value": "11", "title": "臊子面"}
                    ,{"value": "12", "title": "凉皮"}
                    ,{"value": "13", "title": "羊肉泡馍"}
                    ,{"value": "14", "title": "冰糖葫芦", "disabled": true}
                    ,{"value": "15", "title": "狼牙土豆"}
                ]
                ,id: 'demo1' //定义索引

            });

        });
    </script>
@endsection
@extends('common.form')
