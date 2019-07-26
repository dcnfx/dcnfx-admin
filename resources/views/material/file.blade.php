@section('title', '素材列表')
@section('header')
    <div class="layui-inline">
        <select name="folder">
            <option value="">{{ $input['folder'] ?? '请选择一个项目路径' }} </option>
            @foreach($project_list as $item)
                <option value="{{$item}}">{{$item}}</option>
            @endforeach
        </select>
    </div>
    <div class="layui-inline">
        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">保存</button>
    </div>
@endsection
@section('table')
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md5">
            <h3>全部文件列表</h3>
            <table class="layui-hide" id="left_tab" lay-filter="left"></table>
        </div>
        <div class="layui-col-md2 btn-height">
            <div style="margin-bottom: 10px;">
                <button class="layui-btn  layui-btn-disabled left-btn">
                    <i class="layui-icon layui-icon-next"></i>
                </button>
            </div>
            <div >
                <button class="layui-btn layui-btn-disabled right-btn">
                    <i class="layui-icon layui-icon-prev"></i>
                </button>
            </div>
        </div>
        <div class="layui-col-md5">
            <h3>需要的项目列表</h3>
            <table class="layui-hide" id="right_tab" lay-filter="right"></table>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use('table', function(){
            var table = layui.table,$ = layui.$;
            var height = 490; //固定表格高度
            //计算按钮的高度
            var btn_height = height /2 -44;
            $('.btn-height').css('padding-top',btn_height).css('text-align','center')
            //左边表格
            table.render({
                elem: '#left_tab'
                ,url: '{{route('admin.materials.data')}}'
                ,where:{type:1}
                ,cols: [[
                    {checkbox: true, fixed: true}
                    ,{field:'id', title: 'ID', width:80, sort: true, fixed: true}
                    ,{field:'filename', title: '文件名'}
                    // ,{field:'sex', title: '性别', sort: true}

                ]]
                ,id: 'left_table_id'
                ,page: true
                ,height: height
            });
            //右边表格
            table.render({
                elem: '#right_tab'
                ,url: '{{route('admin.materials.data')}}'
                ,where:{type:2}
                ,cols: [[
                    {checkbox: true, fixed: true}
                    ,{field:'id', title: 'ID', width:80, sort: true, fixed: true}
                    ,{field:'filename', title: '文件名'}
                    // ,{field:'sex', title: '性别', sort: true}

                ]]
                ,data: []
                ,id: 'right_table_id'
                ,page: true
                ,height: height
            });
            //公共方法
            function getTableData(id){
                var checkStatus = table.checkStatus(id)
                    ,data = checkStatus.data;
                return data;
            }
            function btnIf(data,btn){
                if(data && data.length){
                    $(btn).removeClass('layui-btn-disabled')
                }else{
                    $(btn).addClass('layui-btn-disabled')
                }
            }
            //重载左边表格
            function reloadTable() {
                var id = layui.data('tabData').id;
                var id_str = id.join(',');
                table.reload('left_table_id', {
                    page:{curr:1},
                    where: {
                        id: id_str
                    }
                });
                table.reload('right_table_id', {
                    page:{curr:1},
                    where: {
                        id: id_str
                    }
                });
            }
            //监听左表格选中
            table.on('checkbox(left)', function(obj){
                btnIf(getTableData('left_table_id'),'.left-btn')
            });
            //监听右表格选中
            table.on('checkbox(right)', function(obj){
                btnIf(getTableData('right_table_id'),'.right-btn')
            });
            //左按钮点击移动数据
            $('.left-btn').on('click',function(){
                if(!$(this).hasClass('layui-btn-disabled')){
                    $('.left-btn,.right-btn').addClass('layui-btn-disabled')
                    var data = getTableData('left_table_id');
                    //查询缓存是否存在数据
                    var id = layui.data('tabData').id;
                    $.each(data,function(k,v){
                        id.push(v.id)
                    });
                    //存储缓存
                    layui.data('tabData',{key: 'id',value: id})
                    reloadTable()
                }
            })
            //右按钮点击移动数据
            $('.right-btn').on('click',function () {
                if(!$(this).hasClass('layui-btn-disabled')){
                    $('.left-btn,.right-btn').addClass('layui-btn-disabled')
                    var data = getTableData('right_table_id');
                    //查询缓存存在的数据
                    var id = layui.data('tabData').id;
                    $.each(data,function(k,v){
                        id.splice($.inArray(v.id,id),1) //删除选中的
                    });
                    //存储缓存
                    layui.data('tabData',{ key: 'id',value: id })
                    reloadTable()
                }
            })
            //左表数据事件
            table.on('rowDouble(left)', function(obj){
                var id = layui.data('tabData').id;
                id.push(obj.data.id)
                layui.data('tabData',{ key: 'id',value: id })
                reloadTable()
            });
            //右表双击时间
            table.on('rowDouble(right)', function(obj){
                var id = layui.data('tabData').id;
                id.splice($.inArray(obj.data.id,id),1) //删除选中的
                layui.data('tabData',{ key: 'id',value: id })
                reloadTable()
            });
            //默认给值1
            layui.data('tabData',{key:'sumit',value:'1'})
            $('.submit').on('click',function () {
                //提交后改成O
                layui.data('tabData',{key:'sumit',value:'0'})
                //关闭窗口
                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                parent.layer.close(index); //再执行关闭
            })
            //再次进来赋值
            var id = layui.data('tabData').id;
            if(id && id.length){
                reloadTable();
            }
        });
    </script>
@endsection
@extends('common.list')