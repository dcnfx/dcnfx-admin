@section('title', '项目列表')
@section('header')
    <div class="layui-inline">
        <button class="layui-btn layui-btn-sm layui-btn-normal addBtn" data-url="{{route('project.edit',0)}}" data-desc="新建项目" ><i class="layui-icon layui-icon-add-1"></i></button>
        <button class="layui-btn layui-btn-sm layui-btn-warm freshBtn"><i class="layui-icon layui-icon-refresh-3"></i></button>
    </div>
@endsection
@section('table')
    <table class="layui-table" lay-even lay-skin="nob">
        <thead>
        <tr>
            <th>ID</th>
            <th>项目名称</th>
            <th>模型质量</th>
            <th>模型质量</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $info)
            <tr>
                <td>{{$info->id }}</td>
                <td>{{$info->folder}} | {{$info->title}}</td>
                <td>{{json_decode($info->filelist,true)["model_quality"]}}</td>
                <td>{{json_decode($info->filelist,true)["texture_quality"]}}</td>
                <td>{{$info->created_at}}</td>
                <td>
                    <button class="layui-btn layui-btn-sm layui-btn-normal edit-btn" data-id="{{$info->id}}" data-desc="查看效果" data-url="{{route('project.show',$info->id)}}"><i class="layui-icon layui-icon-website"></i></button>
                    <button class="layui-btn layui-btn-sm layui-btn-normal edit-btn" data-desc="编辑项目"  data-url="{{route('project.edit',$info->id)}}"  data-id="{{$info->id}}" ><i class="layui-icon layui-icon-edit"></i></button>
                    <button class="layui-btn layui-btn-sm layui-btn-danger del-btn" data-id="{{$info->id}}" data-url="{{route('project.destroy', $info->id)}}"><i class="layui-icon layui-icon-delete"></i></button>
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


            // laydate.render({istoday: true});
            table.init('materials', {
                limit: 50 //注意：请务必确保 limit 参数（默认：10）是与你服务端限定的数据条数一致
                //支持所有基础参数
            });


            form.render();
            form.on('submit(formDemo)', function(data) {
                console.log(data);
            });
        });
    </script>
@endsection
@extends('common.list')