@section('title', '用户列表')
@section('header')
    <div class="layui-inline">
    <button class="layui-btn layui-btn-sm layui-btn-normal addBtn" data-desc="添加用户" data-url="{{route('users.edit', 0)}}"><i class="layui-icon layui-icon-add-1"></i></button>
    <button class="layui-btn layui-btn-sm layui-btn-warm freshBtn"><i class="layui-icon layui-icon-refresh-3"></i></button>
    </div>
@endsection
@section('table')
    <table class="layui-table" lay-even lay-skin="nob">
        <colgroup>
            <col class="hidden-xs" width="50">
            <col class="hidden-xs" width="200">
            <col class="hidden-xs" width="150">
            <col>
            <col class="hidden-xs" width="150">
            <col width="200">
        </colgroup>
        <thead>
        <tr>
            <th class="hidden-xs">ID</th>
            <th class="hidden-xs">用户角色</th>
            <th class="hidden-xs">用户名</th>
            <th>邮箱</th>
            <th class="hidden-xs">手机号码</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $info)
            <tr>
                <td class="hidden-xs">{{$info['id'] }}</td>
                <td class="hidden-xs">{{$info['roles'][0]['display_name'] ?? '已删除'}}</td>
                <td class="hidden-xs">{{$info['username']}}</td>
                <td>{{$info['email']}}</td>
                <td class="hidden-xs">{{$info['mobile']}}</td>
                <td>
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layui-btn-normal edit-btn" data-id="{{$info['id']}}" data-desc="修改用户" data-url="{{ route('users.edit', $info['id'])}}"><i class="layui-icon layui-icon-edit"></i></button>
                        <button class="layui-btn layui-btn-sm layui-btn-danger del-btn" data-id="{{$info['id']}}" data-url="{{route('users.destroy', $info['id'])}}"><i class="layui-icon layui-icon-delete"></i></button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section('js')
    <script>
        layui.use(['form', 'jquery','laydate', 'layer'], function() {
            var form = layui.form,
                $ = layui.jquery,
                laydate = layui.laydate,
                layer = layui.layer
            ;
            // laydate.render({istoday: true});
            form.render();
            form.on('submit(formDemo)', function(data) {
                console.log(data);
            });
        });
    </script>
@endsection
@extends('common.list')