<?php

require_once __DIR__ . '/TestLoginTrait.php';

class TestApiLoginLogoutTest extends \Codeception\TestCase\Test
{
    use TestLoginTrait;

    /**
     * @var \ApiTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    private function checkIsLogin($token, $expectedIsLoggedIn = true)
    {
        // Check login
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->haveHttpHeader('X-Api-Token', $token);
        $this->tester->sendPOST('/api/login/check');
        if ($expectedIsLoggedIn === true) {
            $this->tester->seeResponseCodeIs(200);
        } else {
            $this->tester->seeResponseCodeIs(400);
        }

        $this->tester->seeResponseIsJson();
    }

    private function checkGenericLoginData()
    {
        $this->tester->seeResponseJsonMatchesJsonPath('$.status');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.username');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.email');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.registrationId');

        $this->tester->seeResponseJsonMatchesJsonPath('$.profile_refugee');
        $this->tester->seeResponseJsonMatchesJsonPath('$.profile_citizen');
        $this->tester->seeResponseJsonMatchesJsonPath('$.profile_authority');
    }

    public function testLoginUser()
    {
        $this->checkIsLogin('', false);

        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->sendPOST('/api/login', json_encode([
            'email'    => 'user@mailinator.com',
            'password' => '123456',
        ]));

        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->checkGenericLoginData();

        $response = json_decode($this->tester->grabResponse(), true);

        $token = $response['data']['token'];

        // Check login is true
        $this->checkIsLogin($token, true);

        // Do Logout
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->haveHttpHeader('X-Api-Token', $token);
        $this->tester->sendPOST('/api/logout');
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        // Check is logged out
        $this->checkIsLogin($token, false);
    }

    public function doCheckLogout($token)
    {
        // Do Logout
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->haveHttpHeader('X-Api-Token', $token);
        $this->tester->sendPOST('/api/logout');
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        // Check is logged out
        $this->checkIsLogin($token, false);
    }


    public function testLoginRefugee()
    {
        $this->checkIsLogin('', false);

        $token = $this->doLogin('refugee1@mailinator.com', '123456');
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->checkGenericLoginData();

        // Check login is true
        $this->checkIsLogin($token, true);
        $this->doCheckLogout($token);
    }


    public function testLoginAuthority()
    {
        $this->checkIsLogin('', false);

        $token = $this->doLogin('authority1@mailinator.com', '123456');
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->checkGenericLoginData();

        // Check login is true
        $this->checkIsLogin($token, true);
        $this->doCheckLogout($token);
    }

    public function testLoginCitizen()
    {
        $this->checkIsLogin('', false);

        $token = $this->doLogin('citizen1@mailinator.com', '123456');
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->checkGenericLoginData();

        // Check login is true
        $this->checkIsLogin($token, true);
        $this->doCheckLogout($token);
    }
}