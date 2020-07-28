@extends('Backend.Layout.layout')

@section('title', '后台用户管理')

@section('css')

@endsection

@section('content')
    <form class="layui-form" action="/backend/register_user/register_user/update?id={{$result['id']}}" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">
                账号
            </label>
            <div class="layui-input-block">
                <input type="text" name="account" value="{{$result['account']}}" required lay-verify="required" placeholder="请输入账号" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" value="{{$result['password']}}" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">是否可用</label>
            <div class="layui-input-block">
                @foreach(\App\Model\RegisterUser::$isAllow as $k=>$v)
                    @if($k == $result['is_allow'])
                        <input type="radio" required lay-verify="required" name="is_allow" value="{{$k}}" title="{{$v}}" checked>
                    @else
                        <input type="radio" required lay-verify="required" name="is_allow" value="{{$k}}" title="{{$v}}">
                    @endif
                @endforeach
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-submit>
                    提交
                </button>
                <a href="/backend/register_user/register_user/index" class="layui-btn layui-btn-sm layui-btn-primary">
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
