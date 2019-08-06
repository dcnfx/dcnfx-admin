@section('title', '上传素材')
@section('header')
    <h2>上传素材</h2>
@endsection
@section('form')
    <div class="layui-upload">
        <form class="layui-form" id="form-material" >
            {{ csrf_field() }}
            <fieldset class="layui-elem-field layui-field-title">
                <legend>模型压缩参数设置</legend>
            </fieldset>
            <div class="layui-form-item">
                <label class="layui-form-label">是否减面</label>
                <div class="layui-input-inline">
                    <input type="radio" name="is_cutface" value="on" title="是"  checked>
                    <input type="radio" name="is_cutface" value="off" title="否">
                </div>
                <div class="layui-form-mid layui-word-aux">如果选否，将不做任何处理，模型压缩选项默认失效，如果只想压缩模型，请把下面减面参数全部不选中。</div>
            </div>

            <div class="layui-form-item is-cutface">
                <label class="layui-form-label">减面参数</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="model_cutface_list"  value="5k" title="5000" checked>
                    <input type="checkbox" name="model_cutface_list" value="1w" title="10000" checked>
                    <input type="checkbox" name="model_cutface_list" value="5w" title="50000" checked>
                </div>
                <div class="layui-form-mid layui-word-aux">将会同时压缩原始模型，减面5K指的是每个模型最高减到5千，若小于5千，不处理。</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">模型压缩</label>
                <div class="layui-input-block">
                    <input type="radio" name="model_compress" value="dgene" title="DGene算法压缩" checked>
                </div>
            </div>

            <fieldset class="layui-elem-field layui-field-title">
                <legend>贴图压缩参数设置</legend>
            </fieldset>

            <div class="layui-form-item">
                <label class="layui-form-label">贴图压缩</label>
                <div class="layui-input-inline">
                    <input type="radio" name="is_resize_image" value="on" title="是"  checked>
                    <input type="radio" name="is_resize_image" value="off" title="否">
                </div>
                <div class="layui-form-mid layui-word-aux">如果选否，将不做任何处理。</div>
            </div>

            <div class="layui-form-item is-compress">
                <label class="layui-form-label">压缩参数</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="texture_resize_list"  value="512" title="512*512" checked >
                    <input type="checkbox" name="texture_resize_list" value="1024" title="1024*1024" checked />
                    <input type="checkbox" name="texture_resize_list" value="2048" title="2048*2048" checked >
                    <input type="checkbox" name="texture_resize_list" value="4096" title="4096*4096" checked >
                </div>
                <div class="layui-form-mid layui-word-aux">将会同时保留原始贴图。如果原图不到压缩尺寸，不处理，图片处理时间较长。</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">压缩格式</label>
                <div class="layui-input-block">
                    <input type="radio" name="texture_compress" value="webp" title="webp">
                    <input type="radio" name="texture_compress" value="jpg" title="jpg"  checked>
                </div>
            </div>

            <fieldset class="layui-elem-field layui-field-title">
                <legend>素材项目名</legend>
            </fieldset>
            <div class="layui-form-item">
                <label class="layui-form-label">项目路径</label>
                <div class="layui-input-inline" style="width: 300px;">
                    <input type="text" id="project_name" required name="project_name" placeholder="请选择项目名，或输入新的" value="" autocomplete="off" class="layui-input"
                           style="position:absolute;z-index:2;width: calc(100% - 30px);">
                    <select type="text" id="project_name_select" lay-filter="project_name_select" autocomplete="off" placeholder="请输入项目名" lay-verify="required" class="layui-select" lay-search>
                        @foreach($project_list as $item)
                            <option value="{{$item}}">{{$item}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">请输入2-25位字母作为素材的所属项目，该名称仅用于文件路径，不影响后续新建项目</div>
            </div>
        </form>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>素材上传列表</legend>
        </fieldset>
        <div class="layui-input-inline">
        <button type="button" class="layui-btn layui-btn-normal" id="material-upload">选择素材文件</button>
        </div>
        <div class="layui-input-inline">
        <button type="button" class="layui-btn" id="is-show-para">隐藏参数</button>
        </div>
        <div class="layui-upload-list">
            <table class="layui-table" lay-skin="line">
                <colgroup>
                    <col width="250">
                    <col width="150">
                    <col>
                    <col width="150">
                    <col width="255">
                </colgroup>
                <thead>
                    <tr>
                        <th>文件名</th>
                        <th>大小</th>
                        <th style="text-align: center">上传进度</th>
                        <th>状态</th>
                        <th style="text-align: center">操作</th>
                    </tr>
                </thead>
                <tbody id="material-upload-list"></tbody>
            </table>
        </div>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn layui-hide" id="material-upload-listAction">开始上传</button>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form', 'jquery', 'layer','upload','element'], function() {
            var $ = layui.jquery
                ,form = layui.form
                ,upload = layui.upload
                ,layer = layui.layer
                ,element = layui.element;
            form.render();
            form.on('select(project_name_select)', function (data) {
                $("#project_name").val(data.value);
                $("#material-upload-listAction").removeClass("layui-hide");
                form.render();
            });

            $('input').bind('input propertychange', function() {
                var regNumber = /\d+/; //验证0-9的任意数字最少出现1次。
                var regString = /[a-zA-Z]+/; //验证大小写26个字母任意字母最少出现1次。
                var regNumberString = /^[A-Za-z0-9]+$/;
                var input = $.trim($("#project_name").val());
                if(input==null||input==""|| input.length < 2){
                    $("#material-upload-listAction").addClass("layui-hide");
                } else if(input.length > 25){
                    layer.msg("长度不能超过25个字符哦");
                    $("#project_name").val(input.substring(0, 25) );
                }else if(!regNumberString.test(input)) {
                    console.log(regNumberString.test(input));
                    layer.msg("只能输入数字和字母哦");
                    $("#project_name").val("");
                    $("#material-upload-listAction").addClass("layui-hide");
                }
                else {
                    $("#material-upload-listAction").removeClass("layui-hide");
                }
            });


            //多文件列表示例
            var demoListView = $('#material-upload-list');
            var files;
            var uploadListIns = upload.render({
                elem: '#material-upload'
                ,url: '{{route('admin.materials.upload.store')}}'
                ,accept: 'file'
                ,size: 1024 * 300  //300MB
                ,exts: 'jpg|png|jpeg|obj|mtl|dgene|webp|gltf|glb'
                ,multiple: true
                ,auto: false
                ,bindAction: '#material-upload-listAction'
                ,choose: function(obj){
                    files = obj.pushFile(); //将每次选择的文件追加到文件队列
                    //读取本地文件
                    obj.preview(function(index, file, result){
                        var tr = $(['<tr id="upload-'+ index +'">'
                            ,'<td>'+ file.name +'</td>'
                            ,'<td>'+ formatFileSize(file.size) +'</td>'
                            ,'<td><div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="progress_'+index+'" ><div class="layui-progress-bar layui-bg-green"  lay-percent="0%"></div></div></td>'
                            ,'<td>等待上传</td>'
                            ,'<td>'
                            ,'<button class="layui-btn layui-btn-sm test-upload-demo-reload layui-hide">重传</button>'
                            ,'<button class="layui-btn layui-btn-sm layui-btn-danger test-upload-demo-delete">删除</button>'
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
                ,xhr: function (index, e) {
                    var percent = e.loaded / e.total;//计算百分比
                    element.progress('progress_' + index + '', formatPercentage(percent));
                    var tds = demoListView.find('tr#upload-'+ index).children();
                    tds.eq(3).html('正在上传中');
                    if(formatPercentage(percent) === '100%'){
                        tds.eq(3).html('压缩处理中');
                    }
                }
                ,before: function (obj) {
                    var formJsonData = {};
                    var fields = $('#form-material').serializeArray();
                    $.each( fields, function(i, field){
                        if(!formJsonData[field.name]){
                            formJsonData[field.name] = field.value;
                        }else {
                            formJsonData[field.name] = formJsonData[field.name]+','+field.value;
                        }
                    });
                    this.data = formJsonData;
                }
                ,done: function(res, index, upload){
                    var tds = demoListView.find('tr#upload-'+ index).children();
                    if(res.code == 0){ //上传成功
                        tds.eq(3).html('<span style="color: #5FB878;">'+ res.msg +'</span>');
                        var html = "";
                        if(res.compress_list !== null){
                            var compress_list = res.compress_list.split(',');
                            $.each( compress_list, function(i, item){
                                html +=  "<span class='layui-badge layui-bg-green' style='margin-right:5px'>"+item+"</span>";
                            });
                        }
                        html +=  "<span class='layui-badge layui-bg-green' style='margin-right:5px'>original</span>";
                        tds.eq(4).html(html); //清空操作
                        delete files[index];
                        //return; //删除文件队列已经上传成功的文件
                    } else if(res.code == -1){//上传失败
                        tds.eq(3).html('<span style="color: #FF5722;">'+ res.msg +'</span>');
                        layer.msg(res.msg);
                        this.error(index, upload);
                    }  else{
                        tds.eq(3).html('<span style="color: #FF5722;">上传失败</span>');
                        this.error(index, upload);
                    }
                }
                ,allDone: function(obj){
                    layer.msg('恭喜你，共提交'+obj.total+'个文件，其中成功'+obj.successful+'个，失败'+obj.aborted+'个', {icon: 6});
                }
                ,error: function(index, upload){
                    var tr = demoListView.find('tr#upload-'+ index)
                        ,tds = tr.children();
                    //tds.eq(3).html('<span style="color: #FF5722;">上传失败</span>');
                    tds.eq(4).find('.test-upload-demo-reload').removeClass('layui-hide'); //显示重传
                }
            });
            $("#material-upload").click(function(){
                var project_name = $.trim($("#project_name").val());
                if (project_name =="") {
                    layer.msg("项目名称不能为空，无法提交文件哦");
                }
                else {
                    $("#material-upload-listAction").removeClass("layui-hide");
                }
            });
            $("#is-show-para").click(function () {
                if($("#form-material").is(':visible')){
                    $("#form-material").hide();
                    $("#is-show-para").html("显示参数");
                }
                else{
                    $("#form-material").show();
                    $("#is-show-para").html("隐藏参数");
                }
            });
        });
    </script>
@endsection
@extends('common.form')
