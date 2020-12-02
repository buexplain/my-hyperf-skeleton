<?php

declare (strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Exception\InvalidArgumentException;
use App\Patch\Logger\Logger;
use App\Request\FooRequest;
use Hyperf\Guzzle\ClientFactory;
use App\Services\TestQueueService;
use Hyperf\Di\Annotation\Inject;
class IndexController extends AbstractController
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct(...func_get_args());
        }
        $this->__handlePropertyHandler(__CLASS__);
    }
    public function index(FooRequest $request)
    {
        $request->validated();
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        return ['method' => $method, 'message' => "Hello {$user}."];
    }
    public function test(ClientFactory $clientFactory)
    {
        $options = ['timeout' => 20];
        $client = $clientFactory->create($options);
        $resp = $client->get('http://192.168.3.6:8080/hello');
        return $resp->getBody()->getContents();
    }
    /**
     * @Inject
     * @var TestQueueService
     */
    protected $testQueueService;
    public function queue()
    {
        $this->testQueueService->push(['group@hyperf.io', 'https://doc.hyperf.io', 'https://www.hyperf.io']);
        return $this->success();
    }
    public function log($level)
    {
        switch ($level) {
            case 'error':
                Logger::error('告警');
                break;
            case 'debug':
                Logger::debug('告警');
                break;
            case 'info':
                Logger::info('告警');
                break;
            default:
                throw new InvalidArgumentException('level');
        }
        \Swoole\Coroutine::create(function () {
            sleep(20);
            Logger::error('延迟告警');
        });
        return $this->success('suc');
    }
}