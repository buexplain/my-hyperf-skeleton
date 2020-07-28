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
return [
    //自定义 CoreMiddleWare 的行为
    Hyperf\HttpServer\CoreMiddleware::class => App\Middleware\HttpServer\CoreMiddleware::class,
];
