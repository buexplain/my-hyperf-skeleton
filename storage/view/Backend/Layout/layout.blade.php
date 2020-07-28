<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', '后台管理')</title>
    <link rel="icon" href="data:image/ico;base64,aWNv">
    <link rel="stylesheet" href="/public/layui/css/layui.css">
    <link rel="stylesheet" href="/public/backend/layout/layout.css">
    @yield('css', '')
</head>
<body>
@yield('content')
<script type="text/javascript" src="/public/jquery-1.11.3.min.js"></script>
<script src="/public/layui/layui.js"></script>
<script type="text/javascript" src="/public/layui-config.js"></script>
<script>
    layui.use('util', function(){
        var util = layui.util;
        util.fixbar();
    });
</script>
@yield('js', '')
</body>
</html>