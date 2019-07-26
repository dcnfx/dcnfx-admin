<ul class="layui-nav layui-nav-tree" lay-filter="leftNav">
    @foreach ($menus as $menu)
        @if($menu['show'] == 1)
        <li class="layui-nav-item">
            <a href="javascript:;"><i class="layui-icon {{$menu['icon'] }}"></i>  {{$menu['title']}}</a>
            <dl class="layui-nav-child {{ $menu['id'] == $parent_id ? 'layui-nav-itemed' : '' }}">
                @if (isset($menu['children']) && !empty($menu['children']))
                    @foreach ($menu['children'] as $child)
                        @if($child['show'] == 1)
                        <dd><a href="javascript:;" data-url="{{url($child['uri'])}}" data-id='{{$child['id']}}' data-text="{{ $child['title'] }}"><i class="layui-icon layui-btn-small {{ $child['icon'] }}"></i>  {{ $child['title'] }}</a></dd>
                        @endif
                    @endforeach
                @endif
            </dl>
        </li>
        @endif
    @endforeach
</ul>