@extends('Backend.Layout.layout')

@section('title', '后台权限节点管理')

@section('css')
   <link rel="stylesheet" href="/public/zTree/css/zTreeStyle/zTreeStyle.css">
   <style type="text/css">
      .ztree * {
         font-size: 15px;
      }
      .ztree li a {
         cursor: default;
      }
      .ztree li span {
         cursor: pointer;
      }
      .ztree li span.button.add {margin-left:8px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
      .ztree li span.button.edit {margin-left:8px; margin-right: -1px; background-position:-112px -48px; vertical-align:top; *vertical-align:middle}
      .ztree li span.button.remove {margin-left:8px; margin-right: -1px; background-position:-112px -64px; vertical-align:top; *vertical-align:middle}
   </style>
@endsection

@section('content')
   <blockquote class="layui-elem-quote">
      <a class="layui-btn layui-btn-sm" href="javascript:;" id="j-add">插入顶级节点</a>
      <a class="layui-btn layui-btn-sm layui-btn-normal" href="javascript:;" id="j-switch">关闭丨展开</a>
   </blockquote>
   <ul id="j-tree" class="ztree"></ul>
@endsection

@section('js')
   <script src="/public/zTree/js/jquery.ztree.all.min.js"></script>
   <script>
      var layer = null;
      layui.use(['jquery', 'form', 'layer'], function() {
         layer = layui.layer;
      });
      var zTreeObj;
      // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
      var setting = {
         data: {
            simpleData: {
               enable: true,
               idKey: 'id',
               pIdKey: 'pid',
               rootPId: 0
            }
         }
         ,async: {
            enable: true,
            url:"/backend/rbac/node/lists",
            type:"get",
            autoParam:["id"],
            dataFilter: function (treeId, parentNode, json) {
               if(json.code === 0) {
                  for(var i in json.data) {
                     json.data[i]['isParent'] = json.data[i]['is_parent'] === 1;
                  }
                  return json.data;
               }
               return null;
            }
         }
         ,edit: {
            enable: true,
            showRemoveBtn: false,
            showRenameBtn: false,
            drag: {
               autoExpandTrigger: true,
               prev: true,
               inner: true,
               next: true
            }
         }
         ,view: {
            expandSpeed:"fast",
            addHoverDom: addHoverDom,
            removeHoverDom: removeHoverDom,
            selectedMulti: false
         }
         ,callback: {
            beforeAsync: beforeAsync,
            onAsyncSuccess: onAsyncSuccess,
            onAsyncError: onAsyncError,
            onDrop: onDrop
         }
      };

      /**
       * 移动树
       */
      function onDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
         if(['inner', 'prev', 'next'].lastIndexOf(moveType) === -1) {
            return;
         }
         var param = {
            moveId:treeNodes[0].id,
            targetPid:treeNodes[0].pid
         };
         $.post('/backend/rbac/node/move', param, function (json) {
            if(json.code !==0) {
               layer.alert(json.message, {title:'错误', icon: 2, shade: [0.1, '#fff']});
            }
         });
      }

      // 展开全部
      var expandObj = {
         curAsyncCount: 0,
         asyncForAll: false,
         curStatus:'init',
         isExpandAll: false,
         expandNodes: function (nodes) {
            if (!nodes) return;
            expandObj.curStatus = "expand";
            var zTree = $.fn.zTree.getZTreeObj("j-tree");
            for (var i=0, l=nodes.length; i<l; i++) {
               zTree.expandNode(nodes[i], true, false, false);
               if (nodes[i].isParent && nodes[i].zAsync) {
                  expandObj.expandNodes(nodes[i].children);
               }
            }
         },
         expandAll: function () {
            if (expandObj.curAsyncCount > 0) {
               return;
            }
            var zTree = $.fn.zTree.getZTreeObj("j-tree");
            if (expandObj.asyncForAll) {
               expandObj.curStatus = "init";
               if(expandObj.isExpandAll) {
                  expandObj.isExpandAll = false;
                  zTree.expandAll(expandObj.isExpandAll);
               }else{
                  expandObj.isExpandAll = true;
                  zTree.expandAll(expandObj.isExpandAll);
               }
            } else {
               expandObj.expandNodes(zTree.getNodes());
            }
         }
      };

      /**
       * 异步加载前回调
       */
      function beforeAsync() {
         expandObj.curAsyncCount += 1;
      }

      /**
       * 异步加载成功回调
       */
      function onAsyncSuccess(event, treeId, treeNode, msg) {
         expandObj.curAsyncCount--;
         if(treeNode && expandObj.curStatus === "expand") {
            expandObj.expandNodes(treeNode.children);
         }
         if (expandObj.curAsyncCount <= 0) {
            if (expandObj.curStatus === "expand") {
               expandObj.asyncForAll = true;
               expandObj.isExpandAll = true;
            }
            expandObj.curStatus = "";
         }
      }

      /**
       * 异步加载失败回调
       */
      function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
         expandObj.curAsyncCount--;
         if (expandObj.curAsyncCount <= 0) {
            expandObj.curStatus = "";
            if (treeNode!=null) {
               expandObj.asyncForAll = true;
            }
         }
      }

      /**
       * 刷新节点的父节点的所有子节点
       * @param node
       */
      function reAsyncChildNodes(node) {
         if(reAsyncChildNodes.index) {
            layer.close(reAsyncChildNodes.index);
         }
         var zTree = $.fn.zTree.getZTreeObj("j-tree");
         var treeNode = zTree.getNodesByParam('id', node.pid);
         if(treeNode.length) {
            treeNode = treeNode[0];
            treeNode.isParent = true;
            zTree.reAsyncChildNodes(treeNode, "refresh", false);
         }
      }

      /**
       * 更新节点数据
       * @param node
       */
      function updateNode(node) {
         if(reAsyncChildNodes.index) {
            layer.close(reAsyncChildNodes.index);
         }
         var zTree = $.fn.zTree.getZTreeObj("j-tree");
         var treeNode = zTree.getNodesByParam('id', node.id);
         if(treeNode.length) {
            treeNode = treeNode[0];
            $.extend(true, treeNode, node);
            zTree.updateNode(treeNode);
         }
      }

      /**
       * 鼠标悬浮
       * @param treeId
       * @param treeNode
       */
      function addHoverDom(treeId, treeNode) {
         if($("#addBtn_"+treeNode.tId).length>0 || $("#editBtn_"+treeNode.tId).length>0 || $("#removeBtn_"+treeNode.tId).length>0) {
               return;
         }
         var btnStr = "<span class='button add' id='addBtn_" + treeNode.tId + "' title='add node' onfocus='this.blur();'></span>";
         btnStr += "<span class='button edit' id='editBtn_" + treeNode.tId + "' title='edit node' onfocus='this.blur();'></span>";
         if(!treeNode.isParent) {
            btnStr += "<span class='button remove' id='removeBtn_" + treeNode.tId + "' title='remove node' onfocus='this.blur();'></span>";
         }
         $("#" + treeNode.tId + "_span").after(btnStr);
         var addBtn = $("#addBtn_"+treeNode.tId);
         if (addBtn) {
            addBtn.bind("click", function() {
               reAsyncChildNodes.index = layer.open({
                  title:'新增',
                  type: 2,
                  area: ['700px', '450px'],
                  fixed: false,
                  maxmin: true,
                  content: '/backend/rbac/node/create?pid='+treeNode.id
               });
            });
         }

         var editBtn = $("#editBtn_"+treeNode.tId);
         if (editBtn) {
            editBtn.bind("click", function() {
               reAsyncChildNodes.index = layer.open({
                  title:'编辑',
                  type: 2,
                  area: ['700px', '450px'],
                  fixed: false,
                  maxmin: true,
                  content: '/backend/rbac/node/edit?id='+treeNode.id
               });
            });
         }

         if(!treeNode.isParent) {
            var removeBtn = $("#removeBtn_"+treeNode.tId);
            if (removeBtn) {
               removeBtn.bind("click", function() {
                  layer.confirm('此操作不可撤销，你确定执行吗？', {icon: 3, title:'询问'}, function(index) {
                        layer.close(index);
                        $.post('/backend/rbac/node/destroy?id='+treeNode.id, {}, function (json) {
                           if(json.code === 0) {
                              var zTree = $.fn.zTree.getZTreeObj("j-tree");
                              zTree.removeNode(treeNode);
                           }else{
                              layer.alert(json.message, {title:'错误', icon: 2, shade: [0.1, '#fff']});
                           }
                        });
                     },function (index) {
                        layer.close(index);
                     }
                  );
               });
            }
         }
      }

      /**
       * 鼠标移开
       * @param treeId
       * @param treeNode
       */
      function removeHoverDom(treeId, treeNode) {
         $("#addBtn_"+treeNode.tId).unbind().remove();
         $("#editBtn_"+treeNode.tId).unbind().remove();
         $("#removeBtn_"+treeNode.tId).unbind().remove();
      }

      /**
       * 插入顶级节点
       */
      $("#j-add").on('click', function () {
         $.post('/backend/rbac/node/store', {pid:0, name:'新的节点', url:'javascript:;', is_menu:1}, function (json) {
            if(json.code === 0) {
               window.location.reload();
            }else{
               layer.alert(json.message, {title:'错误', icon: 2, shade: [0.1, '#fff']});
            }
         });
      });

      /**
       * 关闭展开
       */
      $("#j-switch").on('click', function () {
         expandObj.expandAll();
      });

      $(document).ready(function(){
         zTreeObj = $.fn.zTree.init($("#j-tree"), setting);
      });
   </script>
@endsection
