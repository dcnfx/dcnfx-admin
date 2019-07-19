@section('title', '上传素材')
@section('header')
    <h2>上传素材</h2>
@endsection
@section('form')
    <div class="layui-upload">
        <div class="layui-inline">
            <button type="button" class="layui-btn layui-btn-normal" id="material-upload">选择多文件</button>

            <div class="layui-inline">
                <label class="layui-form-label">项目名称</label>
                <div class="layui-input-inline">
                    <input type="text" id="proname" lay-verify="required" value="{{ $input['proname'] ?? '' }}" name="proname"  lay-reqtext="项目名是必填项，岂能为空？" autocomplete="off" class="layui-input" style="width: 400px">
                </div>
            </div>
        </div>
        <div class="layui-upload-list">
            <table class="layui-table">
                <thead>
                <tr><th>文件名</th>
                    <th>大小</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr></thead>
                <tbody id="material-upload-list"></tbody>
            </table>
        </div>
        <div class="layui-inline">
            <button type="button" class="layui-btn" id="material-upload-listAction">开始上传并提交</button>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form', 'jquery', 'layer','upload'], function() {
            var $ = layui.jquery
                ,upload = layui.upload
                ,layer = layui.layer;
            //多文件列表示例
            var demoListView = $('#material-upload-list')
                ,uploadListIns = upload.render({
                elem: '#material-upload'
                ,url: '{{route('admin.material.upload.store')}}'
                ,data: {
                    "proname": function() {
                        var proname = $.trim($("#proname").val());
                        if (proname == "") {
                            layer.msg('项目名是必填项，岂能为空？', {shift: 6, icon: 5});
                            return false;
                        } else {
                            return proname;
                        }
                    },
                    "_token":"{{ csrf_token() }}"
                }
                ,accept: 'file'
                ,size: 1024 * 100  //100MB
                ,exts: 'jpg|png|gif|bmp|jpeg|obj|mtl'
                ,multiple: true
                ,auto: false
                ,bindAction: '#material-upload-listAction'
                ,choose: function(obj){
                    var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                    //读取本地文件
                    obj.preview(function(index, file, result){
                        var tr = $(['<tr id="upload-'+ index +'">'
                            ,'<td>'+ file.name +'</td>'
                            ,'<td>'+ (file.size/1024).toFixed(1) +' KB</td>'
                            ,'<td>等待上传</td>'
                            ,'<td>'
                            ,'<button class="layui-btn layui-btn-mini test-upload-demo-reload layui-hide">重传</button>'
                            ,'<button class="layui-btn layui-btn-mini layui-btn-danger test-upload-demo-delete">删除</button>'
                            ,'</td>'
                            ,'</tr>'].join(''));

                        //单个重传
                        tr.find('.test-upload-demo-reload').on('click', function(){
                            obj.upload(index, file);
                        });

                        //删除
                        tr.find('.test-upload-demo-delete').on('click', function(){
                            delete files[index]; //删除对应的文件
                            tr.remove();
                            uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                        });

                        demoListView.append(tr);
                    });
                }
                ,done: function(res, index, upload){
                    if(res.code == 0){ //上传成功
                        var tr = demoListView.find('tr#upload-'+ index)
                            ,tds = tr.children();
                        tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                        tds.eq(3).html(''); //清空操作
                        return delete this.files[index]; //删除文件队列已经上传成功的文件
                    }
                    this.error(index, upload);
                }
                ,error: function(index, upload){
                    var tr = demoListView.find('tr#upload-'+ index)
                        ,tds = tr.children();
                    tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                    tds.eq(3).find('.test-upload-demo-reload').removeClass('layui-hide'); //显示重传
                }
            });
        });
    </script>
@endsection
@extends('common.form')
