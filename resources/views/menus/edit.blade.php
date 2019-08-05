@section('title', '菜单编辑')
@section('content')
    <div class="layui-form-item">
        <label class="layui-form-label">上级：</label>
        <div class="layui-input-block">
            <select name="category" lay-verify="required">
                <option value=""></option>
                <option value="0" {{empty($menu)||(isset($menu['parent_id'])&&$menu['parent_id']==0)?'selected':''}}>一级菜单</option>
                @if(is_array($menus)&&$menus)
                    @foreach($menus as $menus_child)
                        <option value="{{$menus_child['id']}}" {{(isset($menu['parent_id'])&&$menu['parent_id'] == $menus_child['id']) ? 'selected' : ''}}>{!! $menus_child==0?'':'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─' !!}{{$menus_child['title']}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">名称：</label>
        <div class="layui-input-block">
            <input type="text" value="{{$menu['title'] ?? ''}}" name="name" required lay-verify="name" placeholder="请输入名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否显示：</label>
        <div class="layui-input-inline">
            <input type="radio" name="is_show" value="1" title="显示" checked>
            <input type="radio" name="is_show" value="0" title="隐藏">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">排序：</label>
        <div class="layui-input-block">
            <input type="number" value="{{$menu['order'] ?? ''}}" name="order" required lay-verify="order" placeholder="请输入数字" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图标：</label>
        <div class="layui-input-block">
            <input type="hidden" name="icon" value="{{$menu['icon'] ?? ''}}" required lay-verify="required" placeholder="请选择图标" autocomplete="off" class="layui-input" readonly>
            <div id="icon" style="margin-top: 4px;"></div>
            <div id="icon_page"></div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">URL：</label>
        <div class="layui-input-block">
            <input type="text" value="{{$menu['uri'] ?? ''}}" name="uri" required lay-verify="uri" placeholder="请输入URL,样式如:/fzs" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色：</label>
        <div class="layui-input-block">
            @foreach($roles as $role)
                <input type="checkbox" value="{{$role['id']}}" lay-filter="roles" required {{isset($menu['roleIds'])&&in_array($role['id'],$menu['roleIds'])?'checked':''}} lay-filter="roles_check" name="roles" title="{{$role['display_name']}}">
            @endforeach
        </div>
    </div>
@endsection
@section('id',$id)
@section('js')
    <script>
        function chose_icon(obj){
            $("input[name='icon']").val($(obj).attr('data-icon'));
            if($(obj).hasClass('layui-btn-warm'))$(obj).removeClass('layui-btn-warm');
            else {
                var icons = $('.fzs-icon');
                icons.each(function(index, item) {
                    $(item).removeClass('layui-btn-warm');
                });
                $(obj).addClass('layui-btn-warm');
            }
        }
        layui.use(['form','laypage', 'layer'], function() {
            var form = layui.form;
            form.render();
            var laypage = layui.laypage
                ,layer = layui.layer;
            var data = [
                ['半星','&#xe6c9;','layui-icon-rate-half'],['星星-空心','&#xe67b;','layui-icon-rate'],['星星-实心','&#xe67a;','layui-icon-rate-solid'],['手机','&#xe678;','layui-icon-cellphone'],['验证码','&#xe679;','layui-icon-vercode'],['微信','&#xe677;','layui-icon-login-wechat'],['QQ','&#xe676;','layui-icon-login-qq'],['微博','&#xe675;','layui-icon-login-weibo'],['密码','&#xe673;','layui-icon-password'],['用户名','&#xe66f;','layui-icon-username'],['刷新-粗','&#xe9aa;','layui-icon-refresh-3'],['授权','&#xe672;','layui-icon-auz'],['左向右伸缩菜单','&#xe66b;','layui-icon-spread-left'],['右向左伸缩菜单','&#xe668;','layui-icon-shrink-right'],['雪花','&#xe6b1;','layui-icon-snowflake'],['提示说明','&#xe702;','layui-icon-tips'],['便签','&#xe66e;','layui-icon-note'],['主页','&#xe68e;','layui-icon-home'],['高级','&#xe674;','layui-icon-senior'],['刷新','&#xe669;','layui-icon-refresh'],['刷新','&#xe666;','layui-icon-refresh-1'],['旗帜','&#xe66c;','layui-icon-flag'],['主题','&#xe66a;','layui-icon-theme'],['消息-通知','&#xe667;','layui-icon-notice'],['网站','&#xe7ae;','layui-icon-website'],['控制台','&#xe665;','layui-icon-console'],['表情-惊讶','&#xe664;','layui-icon-face-surprised'],['设置-空心','&#xe716;','layui-icon-set'],['模板','&#xe656;','layui-icon-template-1'],['应用','&#xe653;','layui-icon-app'],['模板','&#xe663;','layui-icon-template'],['赞','&#xe6c6;','layui-icon-praise'],['踩','&#xe6c5;','layui-icon-tread'],['男','&#xe662;','layui-icon-male'],['女','&#xe661;','layui-icon-female'],['相机-空心','&#xe660;','layui-icon-camera'],['相机-实心','&#xe65d;','layui-icon-camera-fill'],['菜单-水平','&#xe65f;','layui-icon-more'],['菜单-垂直','&#xe671;','layui-icon-more-vertical'],['金额-人民币','&#xe65e;','layui-icon-rmb'],['金额-美元','&#xe659;','layui-icon-dollar'],['钻石-等级','&#xe735;','layui-icon-diamond'],['火','&#xe756;','layui-icon-fire'],['返回','&#xe65c;','layui-icon-return'],['位置-地图','&#xe715;','layui-icon-location'],['办公-阅读','&#xe705;','layui-icon-read'],['调查','&#xe6b2;','layui-icon-survey'],['表情-微笑','&#xe6af;','layui-icon-face-smile'],['表情-哭泣','&#xe69c;','layui-icon-face-cry'],['购物车','&#xe698;','layui-icon-cart-simple'],['购物车','&#xe657;','layui-icon-cart'],['下一页','&#xe65b;','layui-icon-next'],['上一页','&#xe65a;','layui-icon-prev'],['上传-空心-拖拽','&#xe681;','layui-icon-upload-drag'],['上传-实心','&#xe67c;','layui-icon-upload'],['下载-圆圈','&#xe601;','layui-icon-download-circle'],['组件','&#xe857;','layui-icon-component'],['文件-粗','&#xe655;','layui-icon-file-b'],['用户','&#xe770;','layui-icon-user'],['发现-实心','&#xe670;','layui-icon-find-fill'],['loading','&#xe63d;','layui-icon-loading'],['loading','&#xe63e;','layui-icon-loading-1'],['添加','&#xe654;','layui-icon-add-1'],['播放','&#xe652;','layui-icon-play'],['暂停','&#xe651;','layui-icon-pause'],['音频-耳机','&#xe6fc;','layui-icon-headset'],['视频','&#xe6ed;','layui-icon-video'],['语音-声音','&#xe688;','layui-icon-voice'],['消息-通知-喇叭','&#xe645;','layui-icon-speaker'],['删除线','&#xe64f;','layui-icon-fonts-del'],['代码','&#xe64e;','layui-icon-fonts-code'],['HTML','&#xe64b;','layui-icon-fonts-html'],['字体加粗','&#xe62b;','layui-icon-fonts-strong'],['删除链接','&#xe64d;','layui-icon-unlink'],['图片','&#xe64a;','layui-icon-picture'],['链接','&#xe64c;','layui-icon-link'],['表情-笑-粗','&#xe650;','layui-icon-face-smile-b'],['左对齐','&#xe649;','layui-icon-align-left'],['右对齐','&#xe648;','layui-icon-align-right'],['居中对齐','&#xe647;','layui-icon-align-center'],['字体-下划线','&#xe646;','layui-icon-fonts-u'],['字体-斜体','&#xe644;','layui-icon-fonts-i'],['Tabs 选项卡','&#xe62a;','layui-icon-tabs'],['单选框-选中','&#xe643;','layui-icon-radio'],['单选框-候选','&#xe63f;','layui-icon-circle'],['编辑','&#xe642;','layui-icon-edit'],['分享','&#xe641;','layui-icon-share'],['删除','&#xe640;','layui-icon-delete'],['表单','&#xe63c;','layui-icon-form'],['手机-细体','&#xe63b;','layui-icon-cellphone-fine'],['聊天 对话 沟通','&#xe63a;','layui-icon-dialogue'],['文字格式化','&#xe639;','layui-icon-fonts-clear'],['窗口','&#xe638;','layui-icon-layer'],['日期','&#xe637;','layui-icon-date'],['水 下雨','&#xe636;','layui-icon-water'],['代码-圆圈','&#xe635;','layui-icon-code-circle'],['轮播组图','&#xe634;','layui-icon-carousel'],['翻页','&#xe633;','layui-icon-prev-circle'],['布局','&#xe632;','layui-icon-layouts'],['工具','&#xe631;','layui-icon-util'],['选择模板','&#xe630;','layui-icon-templeate-1'],['上传-圆圈','&#xe62f;','layui-icon-upload-circle'],['树','&#xe62e;','layui-icon-tree'],['表格','&#xe62d;','layui-icon-table'],['图表','&#xe62c;','layui-icon-chart'],['图标 报表 屏幕','&#xe629;','layui-icon-chart-screen'],['引擎','&#xe628;','layui-icon-engine'],['下三角','&#xe625;','layui-icon-triangle-d'],['右三角','&#xe623;','layui-icon-triangle-r'],['文件','&#xe621;','layui-icon-file'],['设置-小型','&#xe620;','layui-icon-set-sm'],['添加-圆圈','&#xe61f;','layui-icon-add-circle'],['404','&#xe61c;','layui-icon-404'],['关于','&#xe60b;','layui-icon-about'],['箭头 向上','&#xe619;','layui-icon-up'],['箭头 向下','&#xe61a;','layui-icon-down'],['箭头 向左','&#xe603;','layui-icon-left'],['箭头 向右','&#xe602;','layui-icon-right'],['圆点','&#xe617;','layui-icon-circle-dot'],['搜索','&#xe615;','layui-icon-search'],['设置-实心','&#xe614;','layui-icon-set-fill'],['群组','&#xe613;','layui-icon-group'],['好友','&#xe612;','layui-icon-friends'],['回复 评论 实心','&#xe611;','layui-icon-reply-fill'],['菜单 隐身 实心','&#xe60f;','layui-icon-menu-fill'],['记录','&#xe60e;','layui-icon-log'],['图片-细体','&#xe60d;','layui-icon-picture-fine'],['表情-笑-细体','&#xe60c;','layui-icon-face-smile-fine'],['列表','&#xe60a;','layui-icon-list'],['发布 纸飞机','&#xe609;','layui-icon-release'],['对 OK','&#xe605;','layui-icon-ok'],['帮助','&#xe607;','layui-icon-help'],['客服','&#xe606;','layui-icon-chat'],['top 置顶','&#xe604;','layui-icon-top'],['收藏-空心','&#xe600;','layui-icon-star'],['收藏-实心','&#xe658;','layui-icon-star-fill'],     ['关闭-实心','&#x1007;','layui-icon-close-fill'],['关闭-空心','&#x1006;','layui-icon-close'],['正确','&#x1005;','layui-icon-ok-circle'],['添加-圆圈-细体','&#xe608;','layui-icon-add-circle-fine']
                //['播放','&#xe652;'],['播放暂停','&#xe651;'],['音乐','&#xe6fc;'],['视频','&#xe6ed;'],['语音','&#xe688;'],['喇叭','&#xe645;'],['对话','&#xe611;'],['设置','&#xe614;'],['隐身','&#xe60f;'],['搜索','&#xe615;'],['分享','&#xe641;'],['刷新','&#x1002;'],['loading','&#xe63d;'],['loading','&#xe63e;'],['设置','&#xe620;'],['引擎','&#xe628;'],['阅卷错号','&#x1006;'],['错','&#x1007;'],['报表','&#xe629;'],['star','&#xe600;'],['圆点','&#xe617;'],['客服','&#xe606;'],['发布','&#xe609;'],['列表','&#xe60a;'],['图表','&#xe62c;'],['正确','&#x1005;'],['换肤','&#xe61b;'],['在线','&#xe610;'],['右右','&#xe602;'],['左左','&#xe603;'],['表格','&#xe62d;'],['树状','&#xe62e;'],['上传','&#xe62f;'],['添加','&#xe61f;'],['下载','&#xe601;'],['选择模版','&#xe630;'],['工具','&#xe631;'],['添加','&#xe654;'],['编辑','&#xe642;'],['删除','&#xe640;'],['向下','&#xe61a;'],['文件','&#xe621;'],['布局','&#xe632;'],['添加','&#xe608;'],['直播－翻页','&#xe633;'],['404','&#xe61c;'],['轮播组图','&#xe634;'],['帮助','&#xe607;'],['代码','&#xe635;'],['进水','&#xe636;'],['关于','&#xe60b;'],['向上','&#xe619;'],['日期','&#xe637;'],['文件','&#xe61d;'],['top','&#xe604;'],['对','&#xe605;'],['窗口','&#xe638;'],['表情','&#xe60c;'],['正确','&#xe616;'],['文件下载','&#xe61e;'],['图片','&#xe60d;'],['链接','&#xe64c;'],['记录','&#xe60e;'],['文件夹','&#xe622;'],['删除线','&#xe64f;'],['unlink','&#xe64d;'],['编辑_文字','&#xe639;'],['三角','&#xe623;'],['单选框-候选','&#xe63f;'],['单选框-选中','&#xe643;'],['居中对齐','&#xe647;'],['右对齐','&#xe648;'],['左对齐','&#xe649;'],['勾选框（未打勾）','&#xe626;'],['勾选框（已打勾）','&#xe627;'],['加粗','&#xe62b;'],['聊天','&#xe63a;'],['文件夹_反','&#xe624;'],['手机','&#xe63b;'],['表情','&#xe650;'],['html','&#xe64b;'],['表单','&#xe63c;'],['tab','&#xe62a;'],['代码','&#xe64e;'],['字体-下划线','&#xe646;'],['三角','&#xe625;'],['图片','&#xe64a;'],['斜体','&#xe644;'],['好友请求','&#xe612;']
            ];
            var nums = 30;
            var render = function(data,curr){
                var arr = []
                    ,thisData = data.concat().splice(curr*nums-nums, nums);
                //console.log($("input[name='icon']").val().slice(4));
                layui.each(thisData, function(index, item){
                    //console.log(thisData);
                    var iconclass = '';
                    if($("input[name='icon']").val()==item[2])iconclass = 'layui-btn-warm';
                    arr.push('<div class="layui-btn layui-btn-primary layui-btn-sm fzs-icon '+iconclass+'" data-icon="'+item[2]+'" style="margin-bottom: 8px;margin-left:0;margin-right:10px;" onclick="chose_icon(this)" title="'+item[0]+'"><i class="layui-icon '+item[2] +'"></i></div>');
                });
                return arr.join("");

            };
            laypage.render({
                elem: 'icon_page'
                ,count: data.length
                ,limit:nums
                ,groups:2
                ,jump: function(obj){
                    document.getElementById('icon').innerHTML = render(data, obj.curr);
                }
            });
            form.verify({
                name: [/^.{2,12}$/, '菜单名长度2到12位之间'],
                uri: [/^\/(.*)$/, 'URL格式错误'],
            });
            form.on('submit(formDemo)', function(data) {
                var chk_value =[];
                var is_have_admin = 1;
                console.log(data.field);
                $("input[name='roles']:checked").each(function(){
                    chk_value.push($(this).val());
                    if($(this).val()==1)is_have_admin--;
                });
                if(chk_value.length==0){
                    layer.msg('至少选择一个所属角色',{shift: 6,icon:5});
                    return false;
                }
                if(is_have_admin){
                    layer.msg('必选选择超级管理员角色',{shift: 6,icon:5});
                    return false;
                }
                data.field.roles = chk_value;


                $.ajax({
                    url:"{{route('menus.store')}}",
                    data:data.field,
                    type:'post',
                    dataType:'json',
                    success:function(res){
                        if(res.status == 1){
                            layer.msg(res.msg,{icon:6});
                            var index = parent.layer.getFrameIndex(window.name);
                            setTimeout('parent.layer.close('+index+')',2000);
                            //parent.layer.close(index);
                        }else{
                            layer.msg(res.msg,{shift: 6,icon:5});
                        }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        layer.msg('网络失败', {time: 1000});
                    }
                });
                return false;
            });
        });
    </script>
@endsection
@extends('common.edit')
