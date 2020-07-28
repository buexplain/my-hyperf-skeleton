//对layui进行全局配置
layui.config({
    base: '/'
}).extend({
    //后台骨架
    skeleton: 'public/backend/skeleton/skeleton',
    //后台骨架菜单栏部分
    skeletonMenu: 'public/backend/skeleton/skeletonMenu',
    //后台骨架切换卡部分
    skeletonTab: 'public/backend/skeleton/skeletonTab'
});