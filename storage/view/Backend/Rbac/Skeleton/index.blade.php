<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>
        后台管理
    </title>
    <link rel="stylesheet" href="/public/layui/css/layui.css">
    <link rel="stylesheet" href="/public/backend/skeleton/skeleton.css">
    <link rel="icon" href="data:image/ico;base64,aWNv">
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!-- 头部 -->
    <div class="layui-header">
        <div class="layui-logo">
            <a href="">后台管理</a>
        </div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    {{$userInfo['account']}}
                </a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:signOut();">
                    退了
                </a>
            </li>
        </ul>
    </div>
    <!-- 菜单栏 -->
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-filter="left-nav"></ul>
        </div>
    </div>
    <!-- 主体 -->
    <div class="layui-body">
        <div class="layui-tab layui-tab-brief" lay-allowClose="true" lay-filter="top-tab">
            <div class="page-control">
                <a class="btn layui-icon layui-icon-refresh" title="刷新" href="javascript:;" onclick="skeleton.refresh();"></a>
                <a class="btn layui-icon layui-icon-return" title="后退" href="javascript:;" onclick="skeleton.back();"></a>
            </div>
            <ul class="layui-tab-title"></ul>
            <div class="layui-tab-content"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/public/jquery-1.11.3.min.js"></script>
<script src="/public/layui/layui.js"></script>
<script type="text/javascript" src="/public/layui-config.js"></script>
<script type="text/javascript" src="/public/backend/skeleton/index.js"></script>
</body>
</html>