<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="后台管理">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>登录</title>
    <link rel="icon" href="data:image/ico;base64,aWNv">
    <link rel="stylesheet" href="/public/layui/css/layui.css">
    <style>
        .main {
            position: relative;
            width: 100vw;
            height: 100vh;
            color: #d9edf7;
        }
        .bac-layer {
            position: absolute;
            z-index: -1;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: #009688;
        }
        .login {
            right: 0;
            left: 0;
            width: 380px;
            margin: auto;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 2px !important;
        }
        .login form {
            padding: 15px 0 5px;
        }
        .login legend {
            background-color: #009688;
            border: 1px solid #fff;
            font-size: 16px;
            padding: 3px 10px;
            border-radius: 2px;
        }
        .login button{
            border: 1px solid #fff;
            border-radius: 2px;
        }
        .login button:hover {
            box-shadow: 0 0 10px #d9edf7;
        }
        .login img {
            display: block;
            height: 38px;
            border-radius: 2px;
        }
        #j-captcha a {
            color: #d9edf7;
        }
        #j-captcha img {
            background: #d9edf7;
            cursor: pointer;
        }
    </style>
</head>

<body>
<div class="main">
    <div class="bac-layer"></div>
    <fieldset class="layui-elem-field login">
        <legend>
            登 录
        </legend>
        <form class="layui-form" action="/backend/rbac/sign/in" method="post">
            <div class="layui-form-item">
                <label class="layui-form-label">
                    账 号
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="account" required lay-verify="required" value="" placeholder="请输入账号" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">
                    密 码
                </label>
                <div class="layui-input-inline">
                    <input type="password" name="password" required lay-verify="required" value="" placeholder="请输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="signIn">提 交</button>
                </div>
            </div>
        </form>
    </fieldset>
</div>
<script type="text/javascript" src="/public/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/public/layui/layui.js"></script>
<script type="text/javascript" src="/public/layui-config.js"></script>
<script type="text/javascript">
    layui.use(['form', 'jquery'], function() {
        layui.form.on('submit(signIn)', function(data) {
            layer.msg('登录中……');
            return true;
        });
    });
</script>
</body>
</html>