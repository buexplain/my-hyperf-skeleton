@extends('Backend.Layout.layout')

@section('title', '后台用户管理')

@section('css')

@endsection

@section('content')
    <form class="layui-form" action="/backend/rbac/user/store" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">
                账号
            </label>
            <div class="layui-input-block">
                <input type="text" name="account" value="" required lay-verify="required" placeholder="请输入账号" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="text" name="password" value="" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">是否可用</label>
            <div class="layui-input-block">
                @foreach(\App\Model\RbacUser::$isAllow as $k=>$v)
                    @if($k == \App\Model\RbacUser::ALLOW_YES)
                        <input type="radio" name="is_allow" checked value="{{$k}}" title="{{$v}}" required lay-verify="required">
                    @else
                        <input type="radio" name="is_allow" value="{{$k}}" title="{{$v}}" required lay-verify="required">
                    @endif
                @endforeach
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-submit>
                    提交
                </button>
                <a href="/backend/rbac/user/index" class="layui-btn layui-btn-sm layui-btn-primary">
                    取消
                </a>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script>
        layui.use('form', function() {
            var form = layui.form;
        });
    </script>
@endsection
