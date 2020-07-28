<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use App\Services\Rbac\SignService;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\View\RenderInterface;

/**
 * 后台管理的权限校验中间件
 * Class BackendMiddleware
 * @package App\Middleware\Auth
 */
class BackendMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userInfo = SignService::userInfo();
        if(empty($userInfo)) {
            if(!empty($userInfo)) {
                SignService::out();
            }
            /**
             * @var $render RenderInterface
             */
            $render = ApplicationContext::getContainer()->get(RenderInterface::class);
            return $render->render('Jump.302', ['message'=>'权限校验失败，请重新登录', 'url'=>'/backend/rbac/sign/index', 'wait'=>5]);
        }
        return $handler->handle($request);
    }
}