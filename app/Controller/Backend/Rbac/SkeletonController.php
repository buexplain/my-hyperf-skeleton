<?php

declare(strict_types=1);

namespace App\Controller\Backend\Rbac;

use App\Controller\AbstractController;
use App\Model\RbacNode;
use App\Services\Rbac\SignService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\View\RenderInterface;
use Hyperf\Session\Middleware\SessionMiddleware;
use App\Middleware\Auth\BackendMiddleware;

/**
 * 后台管理骨架
 * @AutoController()
 * @Middlewares({
 *     @Middleware(SessionMiddleware::class),
 *     @Middleware(BackendMiddleware::class),
 * })
 */
class SkeletonController extends AbstractController
{
    const VIEW = 'Backend.Rbac.Skeleton.';

    /**
     * 后台骨架主页
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(RenderInterface $render)
    {
        return $render->render(self::VIEW.'index', ['userInfo'=>SignService::userInfo()]);
    }

    /**
     * 返回菜单栏目
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function menu()
    {
        $mod = RbacNode::query()->where('is_menu', RbacNode::MENU_YES);
        $mod->orderBy('sort_by', 'asc');
        $result = $mod->get();
        return $result;
    }
}