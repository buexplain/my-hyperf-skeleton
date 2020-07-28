@extends('Backend.Layout.layout')

@section('title', '后台权限节点管理')

@section('css')

@endsection

@section('content')
    <form class="layui-form" action="/backend/rbac/node/store" method="post">
        <input type="hidden" value="{{$pid}}" name="pid">
        @if(isset($result))
            <div class="layui-form-item">
                <label class="layui-form-label">
                    上级节点
                </label>
                <div class="layui-input-block">
                    <input type="text" value="{{$result->name}}" required lay-verify="required" placeholder="请输入上级节点" readonly autocomplete="off" class="layui-input">
                </div>
            </div>
        @endif

        <div class="layui-form-item">
            <label class="layui-form-label">
                节点名称
            </label>
            <div class="layui-input-block">
                <input type="text" name="name" value="" required lay-verify="required" placeholder="请输入节点名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                节点路径
            </label>
            <div class="layui-input-block">
                <input type="text" name="url" value="" required lay-verify="required" placeholder="请输入节点路径" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">是否为菜单</label>
            <div class="layui-input-block">
                @foreach(\App\Model\RbacNode::$isMenu as $k=>$v)
                    @if($k == \App\Model\RbacNode::MENU_YES)
                        <input type="radio" name="is_menu" checked value="{{$k}}" title="{{$v}}" required lay-verify="required">
                    @else
                        <input type="radio" name="is_menu" value="{{$k}}" title="{{$v}}" required lay-verify="required">
                    @endif
                @endforeach
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                排序
            </label>
            <div class="layui-input-block">
                <input type="number" name="sort_by" value="1991" required lay-verify="required" placeholder="排序" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-filter="j-form" lay-submit>
                    提交
                </button>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script>
        layui.use(['jquery', 'form'], function() {
            var $ = layui.jquery;
            var form = layui.form;
            form.on('submit(j-form)', function(data){
                $.post(data.form.getAttribute('action'), data.field, function (json) {
                    if(json.code === 0) {
                        window.parent.reAsyncChildNodes(json.data);
                    }else{
                        layer.alert(json.message, {title:'错误', icon: 2, shade: [0.1, '#fff']});
                    }
                });
                return false;
            });
        });
    </script>
@endsection
