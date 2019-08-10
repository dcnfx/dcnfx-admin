layui.config({
	base: '/static/admin/js/module/'
}).extend({
	dialog: 'dialog',
});

layui.use(['form', 'jquery', 'laydate', 'layer', 'laypage', 'dialog', 'element'], function() {
	var form = layui.form,
		layer = layui.layer,
		$ = layui.jquery,
		dialog = layui.dialog,
		element = layui.element;
	//获取当前iframe的name值
	var iframeObj = $(window.frameElement).attr('name');
	//全选
	form.on('checkbox(allChoose)', function(data) {
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
        child.each(function(index, item) {
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
	});
	//渲染表单
	form.render();	
	//顶部添加
	$('.addBtn').click(function() {
		var url=$(this).data('url');
		var desc=$(this).data('desc');
		//将iframeObj传递给父级窗口,执行操作完成刷新
		parent.page(desc, url, iframeObj, w = "700px", h = "620px");
		return false;
	}).mouseenter(function() {
		dialog.tips($(this).data('desc'), '.addBtn');
	});





    //顶部刷新
    $('.freshBtn').click(function() {
    	location.reload();
    }).mouseenter(function() {
        dialog.tips('刷新页面', '.freshBtn');
    });

	//列表添加
	$('#table-list').on('click', '.add-btn', function() {
		var url=$(this).data('url');
		//将iframeObj传递给父级窗口
		parent.page("菜单添加", url, iframeObj, w = "700px", h = "620px");
		return false;
	})
	//列表删除
	$('#table-list').on('click', '.del-btn', function() {
		var that = $(this);
		var url = that.data('url');
		var token = $("input[name='_token']").val();
		dialog.confirm({
			message:'您确定要进行删除吗？',
			success:function(){
                $.ajax({
                    url:url,
                    data:{_method: 'DELETE',_token:token},
                    type:'post',
                    dataType:'json',
                    success:function(res){
                        if(res.status == 1){
                            that.parent().parent().parent().remove();
                            $("[parentid='"+that.data('id')+"']").remove();
                            layer.msg(res.msg,{icon:6});
                        }else{
                            layer.msg(res.msg,{shift: 6,icon:5});
                        }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        layer.msg('网络失败', {time: 1000});
                    }
                });
			},
			cancel:function(){
				layer.msg('取消了')
			}
		})
		return false;
	})
	//列表跳转
	$('#table-list,.tool-btn').on('click', '.go-btn', function() {
		var url=$(this).data('url');
		var id = $(this).data('id');
		window.location.href = url+"?id="+id;
		return false;
	})
	$('.go-tab-btn').click(function() {
		var url=$(this).data('url');
		var id = $(this).data('id');
		var text = $(this).data('text');
		if(!url){
			return;
		}
		var isActive = parent.layui.$('.main-layout-tab .layui-tab-title').find("li[lay-id=" + id + "]");
		if(isActive.length > 0) {
			//切换到选项卡
			parent.layui.element.tabChange('tab', id);
		} else {
			parent.layui.element.tabAdd('tab', {
				title: text,
				content: '<iframe src="' + url + '" name="iframe' + id + '" class="iframe" framborder="0" data-id="' + id + '" scrolling="auto" width="100%"  height="100%"></iframe>',
				id: id
			});
			parent.layui.element.tabChange('tab', id);
		}
		parent.layui.$('#main-layout').removeClass('hide-side');
	})
	//编辑栏目
	$('#table-list').on('click', '.edit-btn', function() {
		var url = $(this).data('url');
		var desc = $(this).data('desc');
		//将iframeObj传递给父级窗口
		parent.page(desc, url, iframeObj, w = "700px", h = "620px");
		return false;
	})

	$('.show-tip').mouseenter(function() {
			dialog.tips($(this).data('desc'), $(this));
		});


	//图片预览
	$('#table-list').on('click', '.imageShow', function() {
		var url= $(this).data('url');
		var desc= $(this).data('desc');
		//将iframeObj传递给父级窗口,执行操作完成刷新
		parent.previewImg(desc, url, iframeObj, w = "700px", h = "620px");
		return false;
	});
});

/**
 * 控制iframe窗口的刷新操作
 */
var iframeObjName;

//父级弹出页面
function page(title, url, obj, w, h) {
	if(title == null || title == '') {
		title = false;
	};
	if(url == null || url == '') {
		url = "404.html";
	};
	if(w == null || w == '') {
		w = '700px';
	};
	if(h == null || h == '') {
		h = '350px';
	};
	iframeObjName = obj;
	//如果手机端，全屏显示
	if(window.innerWidth <= 768) {
		var index = layer.open({
			type: 2,
			title: title,
			shadeClose: true,
			area: [320, h],
			fixed: false, //不固定
			content: url,
			end:function(){
                refresh();
			}
		});
		layer.full(index);
	} else {
		var index = layer.open({
			type: 2,
			title: title,
			shadeClose: true,
			area: [w, h],
			fixed: false, //不固定
			content: url,
            end:function(){
                if(title!='管理员信息')refresh();
            }
		});
	}
}



function previewImg(title,url,obj,w, h) {
	iframeObjName = obj;
	var imgHtml = "<img src='" + url + "' />";
	//捕获页
	layer.open({
		type: 1,
		shade: false,
		title: title, //不显示标题
		//area:['600px','500px'],
		area: [w, h],
		content: imgHtml, //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
	});
}

/**
 * 刷新子页,关闭弹窗
 */
function refresh() {
	//根据传递的name值，获取子iframe窗口，执行刷新
	if(window.frames[iframeObjName]) {
		window.frames[iframeObjName].location.reload();
	} else {
		window.location.reload();
	}
	layer.closeAll();
}

/**
 * 刷新排序
 */
function changeSort(name,obj) {
    layui.use(['jquery'], function() {
        var $ = layui.jquery;
        $.ajax({
            url:$(obj).data('url'),
            data:{name: name,val:$(obj).val(),id:$(obj).data('id'),_token:$("input[name='_token']").val()},
            type:'post',
            dataType:'json',
            success:function(res){
                layer.msg(res.msg);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                layer.msg('网络失败', {time: 1000});
            }
        });
    });
}
function formatFileSize(bytes) {
	if (typeof bytes !== 'number') {
		return '';
	}
	if (bytes >= 1000000000) {
		return (bytes / 1000000000).toFixed(2) + ' GB';
	}
	if (bytes >= 1000000) {
		return (bytes / 1000000).toFixed(2) + ' MB';
	}
	return (bytes / 1000).toFixed(2) + ' KB';
}
function formatPercentage(floatValue) {
	return parseFloat(floatValue.toFixed(2)) * 100 + '%';
}

function formatSerializeJson(serializeArrayData) {
	layui.use(['jquery'], function() {
		var $ = layui.jquery;
		var formJsonData = {};
		$.each( serializeArrayData, function(i, field){
			if(!formJsonData[field.name]){
				formJsonData[field.name] = field.value;
			}
			else {
				formJsonData[field.name] = formJsonData[field.name]+','+field.value;
			}
		});
		return formJsonData;
	});
}


//模型标定参数转换

function $0(t) {
	let n = $1(.5 * t.fov),
		r = $2(n),
		u = $3(r),
		x = $4(u, r, t4),
		y = [];

	for (let n = 0; n < x.length; n++) {
		y.push($5(x[n], t.poseMat4));
	}
	for (var z = 1; z < 5; z++) result = $6(result, y[z]);
	return $7(result, .25)
}

function $1(t) {
	return t * t1
}

function $2(t) {
	return t4 * Math.tan(t)
}

function $3(t) {
	return t * t3
}

function $4(t, n, r) {
	return [{
		x: 0,
		y: 0,
		z: 0
	}, {
		x: -t,
		y: -n,
		z: r
	}, {
		x: t,
		y: -n,
		z: r
	}, {
		x: -t,
		y: n,
		z: r
	}, {
		x: t,
		y: n,
		z: r
	}]
}

function $5(t, n) {
	var w = 1 / ( n[12] * t.x + n[13 ] * t.y + n[ 14 ] * t.z + n[ 15 ] );
	return {
		x: (n[0] * t.x + n[1] * t.y + n[2] * t.z + n[3]) *
			w,
		y: (n[4] * t.x + n[5] * t.y + n[6] * t.z + n[7]) *
			w,
		z: (n[8] * t.x + n[9] * t.y + n[10] * t.z + n[11]) * w
	}
}

function $6(t, n) {
	return {
		x: t.x + n.x,
		y: t.y + n.y,
		z: t.z + n.z
	}
}

function $7(t, n) {
	return {
		x: t.x * n,
		y: t.y * n,
		z: t.z * n
	}
}
const t1 = Math.PI / 180,
	t2 = 180 / Math.PI,
	t3 = 16 / 9,
	t4 = 20;
let result = {
	x: 0,
	y: 0,
	z: 0
};