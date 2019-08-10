<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset=UTF-8>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$model->title}}</title>
    <style>
        body {
            background: url("{{$model->background}}") !important;
            width: 100vw !important;
            height: 100vh !important;
            overflow: hidden !important;
            background-size: cover !important;
        }
    </style>
    <script type="text/javascript">
        const apiConfig = {
            init:'/file',
            scene:'/scene',
            fusion:'/fusion',
            flv:'/flv'
        };
        const baseURL = '{{url('admin/project/api/'.$model->id)}}'
        // const apiConfig = {
        //     init:'api/init',
        //     scene:'api/scene',
        //     fuse:'api/fuse',
        //     flv:'api/flv'
        // }
        // const baseURL = '//127.0.0.1:3000/';

        const isLocal = false;
        // isDown 是否开启点击事件
        const isDown = false;
        // loadType
        const loadType = 'url';
        // 动画时间和融合时间
        const animateDuringTime = 6000;
        // 单融合状态下控制开关
        const fusConEnable = true;
        // fov 窗口系数
        const fovWinScale = 1.5;
    </script>
    <link href="/static/show/css/app.6d24eb77.css" rel="stylesheet">
    <script src="/static/show/js/runtime.d7cf3369.js"></script>
    <script src="/static/show/js/2.d7cf3369.js"></script>
    <script src="/static/show/js/app.d7cf3369.js"></script>
</head>
<body onload="onload()">
<div id="loading_overlay" class="loading_overlay">
    <div class="loading_title">
        <div class="loading_title_box">{{$model->title}}</div>
    </div>
    <div class="loading_enter">
        <div id="loading_enter_box">
            <div id="loading_percentage">Enter</div>
            <div id="loading_progress" class="loading_in_progress" style="width:0;"></div>
        </div>
        <div id="loading_help">Welcome!</div>
    </div>
    <div class="loading_footer">
        <div class="loading_footer_box"></div>
    </div>
</div>
<ul id="videoList"></ul>
<script>function onload() {
        // 叠境三维融合库调用示范
        let view = new Dgene({
            callBack: function () {
                view.dgeneScene.initKey();
            }
        });
    }</script>
</body>
</html>