@extends('Backend.Layout.layout')

@section('title', '后台用户管理')

@section('css')

@endsection

@section('content')
   <blockquote class="layui-elem-quote">
      <form class="layui-form">

         <div class="layui-inline">
            <input type="text" name="account" value="" placeholder="请输入搜索的账号" autocomplete="off" class="layui-input">
         </div>

         <div class="layui-inline" style="width: 170px;">
            <select name="is_allow" lay-verify="">
               <option value="0">请选择账号状态</option>
               @foreach(\App\Model\RegisterUser::$isAllow as $k=>$v)
                  <option value="{{$k}}" >{{$v}}</option>
               @endforeach
            </select>
         </div>

         <div class="layui-inline">
            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="j-search">
               搜索
            </button>
         </div>
      </form>
   </blockquote>
   <script type="text/html" id="j-toolbar">
      <div class="layui-btn-container">
         <a class="layui-btn layui-btn-sm" href="/backend/rbac/user/create">新增</a>
      </div>
   </script>
   <table class="layui-hide" id="j-table" lay-filter="j-table"></table>
@endsection

@section('js')
   <script>
      var isAllow = {!! json_encode(\App\Model\RbacUser::$isAllow) !!};
      layui.use(['table','form'], function() {
         var table = layui.table;

         //渲染表格
         var tableIns = table.render({
            elem: '#j-table'
            ,url:window.location.pathname
            ,toolbar: '#j-toolbar' //开启头部工具栏，并为其绑定左侧模板
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,page: {
               curr: 1,
               where:{},
            } //开启分页
            ,parseData: function (json) {
               return {
                  "code": json.code, //解析接口状态
                  "msg": json.message, //解析提示文本
                  "count": json.data.total, //解析数据长度
                  "data": json.data.data //解析数据列表
               };
            }
            ,cols: [[
               {field:'id', title: 'ID', sort: true}
               ,{field:'account', title: '账号'}
               ,{field:'is_allow', title: '是否可用',templet: function (data) {
                     return isAllow[data.is_allow] === undefined ? '未知' : isAllow[data.is_allow];
                  }}
               ,{field:'created_at',  title: '创建时间'}
               ,{fixed: 'right', title:'操作', width:150, templet:function (data) {
                     var btn = '';
                     btn += '<a class="layui-btn layui-btn-sm" href="/backend/rbac/user/edit?id='+data['id']+'">编辑</a>';
                     return btn;
                  }}
            ]]
         });

         //搜索
         layui.form.on('submit(j-search)', function(data) {
            tableIns.reload({
               where: data.field
               ,page: {
                  curr: 1
               }
            });
            return false;
         });
      });
   </script>
@endsection
