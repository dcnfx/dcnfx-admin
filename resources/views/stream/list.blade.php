@section('title', '视频流列表')
@section('header')
    <div class="layui-inline">
        <button class="layui-btn layui-btn-sm layui-btn-normal addBtn" data-url="{{route('stream.edit',0)}}"  data-desc="新建监控" ><i class="layui-icon layui-icon-add-1"></i></button>
        <button class="layui-btn layui-btn-sm layui-btn-warm freshBtn"><i class="layui-icon layui-icon-refresh-3"></i></button>
    </div>
    <div class="layui-inline">
        <input type="text" lay-verify="title" value="{{ $input['title'] ?? '' }}" name="title" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-inline">
        <select name="folder">
            <option value="">{{ $input['folder'] ?? '请选择一个项目路径' }} </option>
            @foreach($project_list as $item)
                <option value="{{$item}}">{{$item}}</option>
            @endforeach
        </select>
    </div>
    <div class="layui-inline">
        <select name="type">
            <option value="">监控类型</option>
            <option value="offline">本地流</option>
            <option value="online">实时流</option>
        </select>
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="begin" autocomplete="off" placeholder="开始日期"  id="begin" value="{{ $input['begin'] ?? '' }}">
    </div>
    <div class="layui-inline">
        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">搜索</button>
    </div>
@endsection
@section('table')
    <table class="layui-table" lay-even lay-skin="nob" lay-filter="streams" id="streams">
        <thead>
        <tr>
            <th>ID</th>
            <th>监控名称</th>
            <th>创建时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $info)
            <tr>
                <td>{{$info->id }}</td>
                <td>{{$info->title}}</td>
                <td>{{$info->created_at}}</td>
                <td><input type="checkbox" name="status" value="1" lay-skin="switch"  data-url="{{route('stream.update',$info->id)}}" lay-text="开放|关闭" {{$info->status == '1'? "checked":''}} onchange="changeStatus('menus',this)"></td>
                <td>
                    <button class="layui-btn layui-btn-sm layui-btn-normal edit-btn" data-text="查看监控"  data-url="{{route('stream.show',$info->id)}}"  ><i class="layui-icon layui-icon-edit"></i></button>
                    <button class="layui-btn layui-btn-sm layui-btn-normal edit-btn" data-text="编辑监控"  data-url="{{route('stream.edit',$info->id)}}"  ><i class="layui-icon layui-icon-edit"></i></button>
                    <button class="layui-btn layui-btn-sm layui-btn-danger del-btn" data-id="{{$info->id}}" data-url="{{route('stream.destroy', $info->id)}}"><i class="layui-icon layui-icon-delete"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section('js')
    <script>
        layui.use(['form', 'jquery','laydate', 'layer','table'], function() {
            var form = layui.form,
                $ = layui.jquery,
                laydate = layui.laydate,
                layer = layui.layer,
                table = layui.table
            ;
            table.render({
                elem: '#streams'

            });


            form.render();
            form.on('submit(formDemo)', function(data) {
                console.log(data);
            });

        });
        function changeStatus(name,obj) {
            layui.use(['jquery'], function() {
                var $ = layui.jquery;
                $.ajax({
                    url:$(obj).data('url'),
                    data:{status: $(obj).val(), _method:"put",_token:$("input[name='_token']").val()},
                    type:'post',
                    dataType:'json',
                    success:function(res){
                        layer.msg(res.msg);
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        layer.msg('网络失败', {time: 1000});
                    }
                });
            });
        }
    </script>
@endsection
@extends('common.list')