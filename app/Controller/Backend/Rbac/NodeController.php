<?php

declare(strict_types=1);

namespace App\Controller\Backend\Rbac;

use App\Controller\AbstractController;
use App\Exception\InvalidArgumentException;
use App\Model\RbacNode;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Session\Middleware\SessionMiddleware;
use App\Middleware\Auth\BackendMiddleware;
use Hyperf\View\RenderInterface;

/**
 * 后台管理骨架
 * @AutoController()
 * @Middlewares({
 *     @Middleware(SessionMiddleware::class),
 *     @Middleware(BackendMiddleware::class),
 * })
 */
class NodeController extends AbstractController
{
    const VIEW = 'Backend.Rbac.Node.';

    public function index(RenderInterface $render)
    {
        if($this->request->getHeaderLine('X-Requested-With') != 'XMLHttpRequest') {
            return $render->render(self::VIEW.'index');
        }
        return $this->success(RbacNode::query()->get());
    }

    public function create(RenderInterface $render)
    {
        $pid = intval($this->request->query('pid', 0));
        if($pid == 0) {
            $result =  null;
        }else{
            $result = RbacNode::query()->select(['name'])->findOrFail($pid);
        }
        return $render->render(self::VIEW.'create', ['pid'=>$pid, 'result'=>$result]);
    }

    public function lists()
    {
        $pid = intval($this->request->query('id', 0));
        return $this->success(RbacNode::query()->where('pid', $pid)->orderBy('sort_by', 'asc')->get());
    }

    public function store()
    {
        $mod = new RbacNode();
        $post = $this->request->post();
        $mod->fill($post);
        if($mod->save()) {
            RbacNode::query()->where('id', $mod->pid)->update(['is_parent'=>RbacNode::PARENT_YES]);
            return $this->success($mod);
        }
        return $this->error("操作失败", 1);
    }

    public function edit(RenderInterface $render)
    {
        $id = $this->request->query('id', 0);
        $result = RbacNode::query()->findOrFail($id);
        return $render->render(self::VIEW.'edit', ['result'=>$result]);
    }

    public function update()
    {
        $id = $this->request->query('id', 0);
        $mod = RbacNode::query()->findOrFail($id);
        $post = $this->request->post();
        $mod->fill($post);
        if($mod->save()) {
            return $this->success($mod);
        }
        return $this->error("操作失败", 1);
    }

    public function destroy()
    {
        $id = $this->request->query('id', 0);
        $mod = RbacNode::query()->findOrFail($id);
        $mod->delete();
        if($mod->pid > 0) {
            if(RbacNode::query()->where('pid', $mod->pid)->count() == 0) {
                RbacNode::query()->where('id', $mod->pid)->update(['is_parent'=>RbacNode::PARENT_NO]);
            }
        }
        return $this->success();
    }

    public function move()
    {
        $moveId = intval($this->request->post('moveId', 0));
        $targetPid = intval($this->request->post('targetPid', 0));
        $targetNode = null;
        if($targetPid > 0) {
            $targetNode = RbacNode::query()->findOrFail($targetPid);
        }
        $node = RbacNode::query()->findOrFail($moveId);
        $oldPid = $node->pid;
        $node->pid = $targetPid;
        $node->save();
        if($oldPid > 0) {
            //检查旧的父id是否还拥有子节点
            if(RbacNode::query()->where('pid', $oldPid)->count() == 0) {
                RbacNode::query()->where('id', $oldPid)->update(['is_parent'=>RbacNode::PARENT_NO]);
            }
        }
        //检查新的父id是否已经拥有了子节点
        if(isset($targetNode) && $targetNode->is_parent != RbacNode::PARENT_YES) {
            $targetNode->is_parent = RbacNode::PARENT_YES;
            $targetNode->save();
        }
        return $this->success();
    }
}