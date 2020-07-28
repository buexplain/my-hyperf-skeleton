<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=11,IE=10,IE=9,IE=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <title>302</title>
    <link rel="icon" href="data:image/ico;base64,aWNv">
</head>
<body>
<style>
    body{
        margin: 0;
        padding: 20px;
        width: 100%;
        height: 100%;
        color: #B0BEC5;
        font-weight: 100;
        font-size: 1.6rem;
    }
    a {
        text-decoration:none
    }
</style>
<div>{{$message}}</div>
@if($url)
    页面自动 <a id="j-href" href="{{$url}}" title="按下enter直接跳转">跳转</a> <a href="javascript:;" onclick="jump.stop()" title="按下space可以暂停">停止</a> 等待时间：<b id="j-wait">{{$wait}}</b>
    <script type="text/javascript">
        var jump = {
            _wait:3,
            _url: '',
            _interval:-1,
            init: function() {
                this._wait = document.getElementById('j-wait');
                this._url = document.getElementById('j-href').href;
                var that = this;
                document.onkeydown = function (event) {
                    var e = event || window.event;
                    if(!e) {
                        return;
                    }
                    if(e.keyCode === 13) {
                        that.go();
                    }else if(e.keyCode === 32) {

                        if(that._interval === -1) {
                            that.start();
                        }else {
                            that.stop();
                        }
                    }
                };
            },
            go: function() {
                window.location.href = this._url;
            },
            start: function() {
                var that = this;
                that._interval = setInterval(function() {
                    var time = --that._wait.innerHTML;
                    if(time <= 0) {
                        that.stop();
                        that.go();
                    }
                }, 1000);
            },
            stop: function() {
                if(this._interval > -1) {
                    clearInterval(this._interval);
                    this._interval = -1;
                }
            }
        };
        jump.init();
        jump.start();
    </script>
@endif
</body>
</html>