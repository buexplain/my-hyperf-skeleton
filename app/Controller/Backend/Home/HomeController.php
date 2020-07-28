<?php

declare(strict_types=1);

namespace App\Controller\Backend\Home;

use App\Controller\AbstractController;
use App\Services\Rbac\SignService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\View\RenderInterface;
use Hyperf\Session\Middleware\SessionMiddleware;
use App\Middleware\Auth\BackendMiddleware;

/**
 * 我的桌面
 * @AutoController()
 * @Middlewares({
 *     @Middleware(SessionMiddleware::class),
 *     @Middleware(BackendMiddleware::class),
 * })
 */
class HomeController extends AbstractController
{
    const VIEW = 'Backend.Home.';

    public function index(RenderInterface $render)
    {
        return $render->render(self::VIEW.'index', ['userInfo'=>SignService::userInfo()]);
    }
}

