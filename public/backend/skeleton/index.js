//初始化页面骨架
var skeleton = null;
$(function () {
    layui.use(['skeleton', 'jquery'], function() {
        $.get('/backend/rbac/skeleton/menu', {}, function (myMenu) {
            try {
                skeleton = layui.skeleton('left-nav', 'top-tab');
                //初始化菜单栏
                skeleton.menu.init(myMenu, 'id', 'pid', 'name', 'url');
                //打开第一个节点
                skeleton.menu.open(myMenu[0].id);
            }catch (e) {
                console.log('初始化后台骨架失败：'+e);
            }
        });
    });
});

/**
 * 退出登录
 */
function signOut() {
    window.location.href = '/backend/rbac/sign/out';
}