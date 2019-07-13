<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>@yield('title') | {{ Config::get('app.name') }}</title>
    <link rel="stylesheet" type="text/css" href="/static/admin/layui/v2.5.4/css/layui.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/admin.css" />
    <script src="/static/admin/layui/v2.5.4/layui.js" type="text/javascript" charset="utf-8"></script>
    <script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
<div class="wrap-container clearfix">
    <div class="column-content-detail">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <form class="layui-form layui-card-header" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline tool-btn">
                                @yield('header')
                            </div>
                            {{ csrf_field() }}
                        </div>
                    </form>
                    <div class="layui-form layui-card-body" id="table-list">
                        @yield('table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




</body>
@yield('js')
</html>