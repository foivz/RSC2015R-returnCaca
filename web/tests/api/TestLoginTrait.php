<?php

/**
 * Created by PhpStorm.
 * User: zoka123
 * Date: 14.11.15.
 * Time: 18:33
 */
trait TestLoginTrait
{
    protected function doLogin($email, $password)
    {
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->sendPOST('/api/login', json_encode([
            'email'    => $email,
            'password' => $password,
        ]));

        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();
        $response = json_decode($this->tester->grabResponse(), true);
        $token = $response['data']['token'];
        return $token;
    }

}