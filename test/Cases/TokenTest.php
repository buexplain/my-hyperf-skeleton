<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/07/28
 * Time: 16:22
 */

declare(strict_types=1);

namespace HyperfTest\Cases;

use App\Constants\ErrorCode;
use HyperfTest\HttpTestCase;

/**
 * Class TokenTest
 * @package HyperfTest\Cases
 */
class TokenTest extends HttpTestCase
{
    use \HyperfTest\Fixture\RegisterUserFixture;

    public static function setUpBeforeClass()
    {
        self::setAdminUser();
    }

    /**
     * @return array
     */
    public function testIn(): array
    {
        $response = $this->client->post('/token/in', [
            'account'=>self::$registerUserAccount,
            'password'=>self::$registerUserPassword,
        ]);
        $this->assertTrue($response['code'] == ErrorCode::SUCCESS);
        return $response;
    }

    /**
     * @depends testIn
     * @param array $token
     * @return array
     */
    public function testRefresh(array $token): array
    {
        $auth = config('token.options.name', 'Authorization');
        $response = $this->client->post('/token/refresh', [], [$auth=>$token['data']['token']]);
        $this->assertTrue($response['code'] == ErrorCode::SUCCESS);
        return $response;
    }

    /**
     * @depends testRefresh
     * @param array $token
     */
    public function testOut(array $token)
    {
        $auth = config('token.options.name', 'Authorization');
        $response = $this->client->delete('/token/out', [], [$auth=>$token['data']['token']]);
        $this->assertTrue($response['code'] == ErrorCode::SUCCESS);
    }

    /**
     * @throws \Exception
     */
    public static function tearDownAfterClass()
    {
        self::tearRegisterUser();
    }
}