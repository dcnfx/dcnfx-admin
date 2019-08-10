@section('title', '素材列表')
@section('header')
    <div class="layui-inline">
        <a class="layui-btn layui-btn-sm layui-btn-normal go-tab-btn" data-url="{{route('admin.materials.upload')}}" data-id="9" data-text="上传素材"  ><i class="layui-icon layui-icon-add-1"></i>上传素材</a>
        <button class="layui-btn layui-btn-sm layui-btn-warm freshBtn"><i class="layui-icon layui-icon-refresh-3"></i></button>
    </div>
    <div class="layui-inline">
        <input type="text" lay-verify="title" value="{{ $input['title'] ?? '' }}" name="title" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-inline">
        <select name="folder">
            <option value="{{$input['folder'] ?? ''}}">{{ $input['folder'] ?? '请选择项目路径' }} </option>
            @foreach($project_list as $item)
                <option value="{{$item}}">{{$item}}</option>
            @endforeach
        </select>
    </div>
    <div class="layui-inline">
        <select name="type">
            <option value="{{$input['type'] ?? ''}}">{{ $input['type'] ?? '选择文件类型' }}</option>
            <option value="model">模型文件</option>
            <option value="texture">贴图文件</option>
            <option value="other">其它文件</option>
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
    <table class="layui-table" lay-even lay-skin="nob" lay-filter="materials">
        <colgroup>
            <col width="50">
            <col>
            <col>
            <col>
            <col>
            <col width="380">
            <col>
        </colgroup>
        <thead>
            <tr>
                <th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose"></th>
                <th>ID</th>
                <th>文件名</th>
                <th>文件大小</th>
                <th>创建时间</th>
                <th>下载地址</th>
                <th>操作</th>
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
                    <a class="layui-btn layui-badge layui-bg-green" href="{{route('admin.materials.download',$info->sign)}}">源文件</a>
                    @foreach ($info->compressed()->get() as $item)
                        @if ($item -> type == "texture" )
                            <button class="layui-btn layui-badge imageShow {{$item->desc=="original"?"layui-bg-blue":"layui-bg-orange"}} " data-url="{{asset('storage/'.$item->path)}}" data-desc="{{ formatSize($item->size)}}">{{$item->desc}}</button>
                        @else
                            <a class="layui-btn layui-badge {{$item->desc=="original"?"layui-bg-blue":"layui-bg-orange"}} " href="{{asset('storage/'.$item->path)}}">{{$item->desc}}</a>
                        @endif
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