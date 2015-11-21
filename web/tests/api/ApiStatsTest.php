<?php

require_once __DIR__ . '/TestLoginTrait.php';

class ApiStatsTest extends \Codeception\TestCase\Test
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

    private function checkStatList()
    {
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->tester->seeResponseJsonMatchesJsonPath('$.status');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data');

        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].team');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].player');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].player.id');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].player.alias');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].player.level');

        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].isLive');

        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].location');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].location.lat');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].location.lng');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.stats[0].location.game');

    }

    public function testGetStats()
    {

//        $token = $this->doLogin('player1@mailinator.com', '123456');
//        $this->tester->haveHttpHeader('X-Api-token', $token);
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->sendGET('/api/stats/1');
        $this->checkStatList();
    }
}