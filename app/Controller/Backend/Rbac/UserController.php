<?php

declare(strict_types=1);

namespace App\Controller\Backend\Rbac;

use App\Controller\AbstractController;
use App\Model\RbacUser;
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
class UserController extends AbstractController
{
    const VIEW = 'Backend.Rbac.User.';

    public function index(RenderInterface $render)
    {
        if($this->request->getHeaderLine('X-Requested-With') != 'XMLHttpRequest') {
            return $render->render(self::VIEW.'index');
        }
        $mod = RbacUser::query();
        if(isset(RbacUser::$isAllow[$this->request->query('is_allow')])) {
            $mod->where('is_allow', (int) $this->request->query('is_allow'));
        }
        if(strlen($this->request->query('account', ''))) {
            $mod->where('account', "like", '%'.$this->request->query('account', '').'%');
        }
        $result = $mod->paginate(intval($this->request->query('limit')));
        return $this->success($result);
    }

    public function create(RenderInterface $render)
    {
        return $render->render(self::VIEW.'create');
    }

    /**
     * 保存
     * @return mixed
     */
    public function store()
    {
        try{
            $mod = new RbacUser();
            $post = $this->request->post();
            $mod->fill($post);
            if($mod->save()) {
                return $this->jump("操作成功", '/backend/rbac/user/index');
            }
            return $this->jump("操作失败", '/backend/rbac/user/create');
        }catch (\Hyperf\Database\Exception\QueryException $e) {
            /**
             * @link https://dev.mysql.com/doc/refman/8.0/en/server-error-reference.html#error_er_dup_entry
             */
            if($e->getCode() == 23000 && stripos($e->getPrevious()->getMessage(), '1062') !== false) {
                return $this->jump("账号已经存在，请重新输入", '/backend/rbac/user/create');
            }else{
                throw $e;
            }
        }
    }

    /**
     * 编辑
     * @param RenderInterface $render
     * @return mixed
     */
    public function edit(RenderInterface $render)
    {
        $id = $this->request->query('id', 0);
        $result = RbacUser::query()->findOrFail($id);
        return $render->render(self::VIEW.'edit', ['result'=>$result]);
    }

    /**
     * 更新
     * @return mixed
     */
    public function update()
    {
        try{
            $id = $this->request->query('id', 0);
            $mod = RbacUser::query()->findOrFail($id);
            $post = $this->request->post();
            $mod->fill($post);
            if($mod->save()) {
                return $this->jump("操作成功", '/backend/rbac/user/index');
            }
            return $this->jump("操作失败", '/backend/rbac/user/edit?id='.$id);
        }catch (\Hyperf\Database\Exception\QueryException $e) {
            /**
             * @link https://dev.mysql.com/doc/refman/8.0/en/server-error-reference.html#error_er_dup_entry
             */
            if($e->getCode() == 23000 && stripos($e->getPrevious()->getMessage(), '1062') !== false) {
                return $this->jump("账号已经存在，请重新输入", '/backend/rbac/user/edit?id='.$id);
            }else{
                throw $e;
            }
        }
    }
}