<div class="main-layout-header">
    <div class="menu-btn" id="hideBtn">
        <a href="javascript:;">
            <i class="layui-icon layui-icon-shrink-right" ></i>
        </a>
    </div>
    <div class="menu-btn" lay-unselect="">
        <a href="/" target="_blank" title="前台">
            <i class="layui-icon layui-icon-website"></i>
        </a>
    </div>
    <ul class="layui-nav" lay-filter="rightNav" style="color: #20222A">
        <li class="layui-nav-item">
            <div class="addBtn hidden-xs" data-desc="管理员信息" data-url="{{route('admin.userinfo')}}"><i class="layui-icon layui-icon-username"></i> {{\Auth::user()->username}}</div>
        </li>
        <li class="layui-nav-item" ><a  style="color: #20222A" href="{{route('admin.logout')}}">退出</a></li>
    </ul>
</div>