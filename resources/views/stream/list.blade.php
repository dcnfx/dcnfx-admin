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
            <option value="{{$input['folder'] ?? ''}}">{{ $input['folder'] ?? '请选择一个项目路径' }} </option>
            @foreach($project_list as $item)
                <option value="{{$item}}">{{$item}}</option>
            @endforeach
        </select>
    </div>
    <div class="layui-inline">
        <select name="type">
            <option value="{{$input['type'] ?? ''}}">{{ $input['type'] ?? '选择监控类型' }}</option>
            <option value="offline">本地流</option>
            <option value="online">实时流</option>
        </select>
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="begin" autocomplete="off" placeholder="开始日期"  id="begin" value="{{ $input['begin'] ?? '' }}">
    </div>
    <div class="layui-inline">
        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">搜索</button>
        <button class="layui-btn layui-btn-primary" type="reset" id="reset" lay-filter="formDemo">重置</button>
    </div>
@endsection
@section('table')
    <table class="layui-table" lay-even lay-skin="nob" id="streams">
        <thead>
        <tr>
            <th>ID</th>
            <th>监控名称</th>
            <th>创建时间</th>
            <th>是否开放</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $info)
            <tr>
                <td>{{$info->id }}</td>
                <td>{{$info->folder }} | {{$info->title}}</td>
                <td>{{$info->created_at}}</td>
                <td><input type="checkbox" name="status" value="1" lay-filter="status" lay-skin="switch"  data-url="{{route('stream.update',$info->id)}}" lay-text="开放|关闭" {{$info->status == '1'? "checked":''}}></td>
                <td>@if($info->type == 'online')
                    <button class="layui-btn layui-btn-xs">实时监控</button>
                    @elseif($info->type == 'offline')
                    <button class="layui-btn layui-btn-primary layui-btn-xs">本地测试</button>
                    @endif
                    <button class="layui-btn layui-btn-warm layui-btn-xs">{{is_numeric($info->keycode)? $info->keycode - 48 : $info->keycode}}</button>
                </td>
                <td>
                    <button class="layui-btn layui-btn-sm edit-btn" data-text="查看监控"  data-url="{{route('stream.show',$info->id)}}"  ><i class="layui-icon layui-icon-play"></i></button>
                    <button class="layui-btn layui-btn-sm layui-btn-normal edit-btn" data-desc="编辑监控"  data-id="{{$info->id}}" data-url="{{route('stream.edit',$info->id)}}"  ><i class="layui-icon layui-icon-edit"></i></button>
                    <button class="layui-btn layui-btn-sm layui-btn-danger del-btn" data-id="{{$info->id}}" data-url="{{route('stream.destroy', $info->id)}}"><i class="layui-icon layui-icon-delete"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="page-wrap">
        {{$list->render()}}
    </div>
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
            laydate.render({
                elem: '#begin'
            });


            form.render();
            form.on('switch(status)', function(data) {
                var status =data.elem.checked?"1":"0" ;
                $.ajax({
                    url:$(this).data('url'),
                    data:{status: status, _method:"put",_token:$("input[name='_token']").val()},
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
            form.on('submit(formDemo)', function(data) {
                console.log(data);
            });
            $('#reset').click(function() {
                $("input[name='begin']").val('');
                $("input[name='title']").val('');
                $("select[name='folder']").val('');
                $("select[name='type']").val('');
                $('form').submit();
            });

        });

    </script>
@endsection
@extends('common.list')