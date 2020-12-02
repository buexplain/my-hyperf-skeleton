<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', \App\Controller\IndexController::class.'@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/test', \App\Controller\IndexController::class.'@test');
Router::addRoute(['GET', 'POST', 'HEAD'], '/queue', \App\Controller\IndexController::class.'@queue');
Router::addRoute(['GET', 'POST', 'HEAD'], '/log/{level}', \App\Controller\IndexController::class.'@log');

//token相关接口
Router::addGroup('/token/',function () {
    Router::post('in', \App\Controller\Token\TokenController::class.'@in');
    Router::addGroup('',function () {
        Router::delete('out', \App\Controller\Token\TokenController::class.'@out');
        Router::post('refresh', \App\Controller\Token\TokenController::class.'@refresh');
    }, [
        'middleware' => [
            //先执行token初始化的中间件
            \Token\Middleware\TokenMiddleware::class,
            //后执行token权限校验的中间件
            \App\Middleware\Auth\ApiMiddleware::class,
        ]
    ]);
});
