<?php

require_once __DIR__ . '/TestLoginTrait.php';

class ApiCaptureFlagTest extends \Codeception\TestCase\Test
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

    public function testCaptureFlag()
    {
        $token = $this->doLogin('player1@mailinator.com', '123456');
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->haveHttpHeader('X-Api-token', $token);

        $region = rand(1, 4);
        $this->tester->sendPOST('/api/capture-flag', json_encode([
            'regionId' => $region
        ]));
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->haveHttpHeader('X-Api-token', $token);
        $this->tester->sendGET('/api/player-status');
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();
        $data = json_decode($this->tester->grabResponse(), true);
        $myTeamId = $data['data']['id'];

        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->sendGET('/api/game-status/1');
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $data = json_decode($this->tester->grabResponse(), true);
        $data = $data['data'];

        $this->assertArrayHasKey('ownerRegion1', $data);
        $this->assertArrayHasKey('ownerRegion2', $data);
        $this->assertArrayHasKey('ownerRegion3', $data);
        $this->assertArrayHasKey('ownerRegion4', $data);

        $this->assertEquals($data['ownerRegion' . $region]['id'], $myTeamId);


    }
}