@section('title', '网站设置')
@section('header')
    <h2>网站设置</h2>
@endsection
@section('form')
    <form class="layui-form" wid100 action="{{route('admin.system.update')}}" method="post">
        {{csrf_field()}}
        {{method_field('put')}}
        <div class="layui-form-item">
            <label for="" class="layui-form-label">站点标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" value="{{ $config['title']??'' }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">上传文件类型</label>
            <div class="layui-input-block">
                <input type="text" name="cache" value="{{ $config['allowFiles']??'' }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">站点关键词</label>
            <div class="layui-input-block">
                <input type="text" name="keywords" value="{{ $config['keywords']??'' }}" lay-verify="required" placeholder="请输入关键词" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">站点描述</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="description" cols="30" rows="10">{{ $config['description']??'' }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">CopyRight</label>
            <div class="layui-input-block">
                <input type="text" name="copyright" value="{{ $config['copyright']??'' }}" lay-verify="required" placeholder="请输入copyright" class="layui-input" >
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

@endsection
@extends('common.form')
