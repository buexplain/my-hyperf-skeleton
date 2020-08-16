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
namespace App\Controller;

use App\Request\FooRequest;
use \Hyperf\Guzzle\ClientFactory;

class IndexController extends AbstractController
{
    public function index(FooRequest $request)
    {
        $request->validated();
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function test(ClientFactory $clientFactory)
    {
        $options = [
            'timeout'=>20
        ];
        $client = $clientFactory->create($options);
        $resp = $client->get('http://192.168.3.6:8080/hello');
        return $resp->getBody()->getContents();
    }
}
