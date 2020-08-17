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

use App\Constants\ErrorCode;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\View\RenderInterface;
use Psr\Container\ContainerInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
abstract class AbstractController
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        self::__handlePropertyHandler(__CLASS__);
    }
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;
    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;
    /**
     * 返回成功的json
     * @param $data
     * @param null $message
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function success($data = [], $message = null)
    {
        $json = ['code' => ErrorCode::SUCCESS, 'message' => is_null($message) ? ErrorCode::getMessage(ErrorCode::SUCCESS) : $message, 'data' => $data ?: (object) []];
        return $this->response->json($json);
    }
    /**
     * 返回错误json
     * @param $message
     * @param int $code
     * @param null $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function error(string $message, int $code, $data = null)
    {
        $data = ['code' => $code, 'message' => $message, 'data' => $data ?: (object) []];
        return $this->response->json($data);
    }
    /**
     * 跳转并提示页面
     * @param string $message
     * @param null $url
     * @param int $wait
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function jump(string $message, $url = null, $wait = 5)
    {
        if (is_null($url)) {
            $url = $this->request->header('Referer', '');
        }
        /**
         * @var $render RenderInterface
         */
        $render = ApplicationContext::getContainer()->get(RenderInterface::class);
        return $render->render('Jump.302', ['message' => $message, 'url' => $url, 'wait' => $wait]);
    }
}