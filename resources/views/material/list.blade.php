@section('title', '素材列表')
@section('header')
    <div class="layui-inline">
        <button data-url="{{route('admin.materials.upload')}}" data-id="9" data-text="上传素材" lay-filter="return-upload" class="layui-btn layui-btn-sm layui-btn-normal"><i class="layui-icon layui-icon-add-1"></i>上传素材</button>
        <button class="layui-btn layui-btn-sm layui-btn-warm freshBtn"><i class="layui-icon layui-icon-refresh-3"></i></button>
    </div>
@endsection
@section('table')
    <table class="layui-table" lay-even lay-skin="nob" lay-filter="materials">
        <thead>
            <tr>
                <th lay-data="{field:'checkbox', width:50}"><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose"></th>
                <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                <th lay-data="{field:'filename', width:230}">文件名</th>
                <th lay-data="{field:'size', width:100}">文件大小</th>
                <th lay-data="{field:'created_at', width:180, sort: true}">创建时间</th>
                <th lay-data="{field:'download',width:310}">下载地址</th>
                <th lay-data="{field:'action'}">操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach($list as $info)
            <tr>
                <td><input type="checkbox" name="" lay-skin="primary" data-id="{{$info->id}}"></td>
                <td>{{$info->id }}</td>
                <td>{{$info->filename.'.'.$info->suffix}}</td>
                <td>{{formatSize($info->size)}}</td>
                <td>{{$info->created_at}}</td>
                <td>
                    <a class="layui-badge layui-bg-green" href="{{asset('storage/'.$info->path)}}">原文件</a>
                    @foreach ($info->compressed()->get() as $item)
                        <a class="layui-badge {{$item->desc=="original"?"layui-bg-blue":"layui-bg-orange"}} " href="{{asset('storage/'.$item->path)}}">{{$item->desc=="original"?"转格式":$item->desc}}</a>
                    @endforeach

                </td>
                <td>
                    <button class="layui-btn layui-btn-sm layui-btn-normal edit-btn" data-id="" data-desc="修改素材" data-url=""><i class="layui-icon layui-icon-edit"></i></button>
                    <button class="layui-btn layui-btn-sm layui-btn-danger del-btn" data-id="{{$info->id}}" data-url="{{route('admin.materials.destroy', $info->id)}}"><i class="layui-icon layui-icon-delete"></i></button>
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
        layui.use(['form', 'jquery','laydate', 'layer','table','element'], function() {
            var form = layui.form,
                $ = layui.jquery,
                laydate = layui.laydate,
                layer = layui.layer,
                table = layui.table,
                element = layui.element
            ;

            element.on('button(return-upload)', function(elem) {
                var id = elem.data('id');
                var url = elem.data('url');
                var text = elem.data('text');
                if(!url){
                    return;
                }
                var isActive = $('.main-layout-tab .layui-tab-title').find("li[lay-id=" + id + "]");
                if(isActive.length > 0) {
                    //切换到选项卡
                    element.tabChange('tab', id);
                } else {
                    element.tabAdd('tab', {
                        title: text,
                        content: '<iframe src="' + url + '" name="iframe' + id + '" class="iframe" framborder="0" data-id="' + id + '" scrolling="auto" width="100%"  height="100%"></iframe>',
                        id: id
                    });
                    element.tabChange('tab', id);
                }
            });


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