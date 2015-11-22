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

        $data = json_decode($this->tester->grabResponse(), true);

        $this->tester->seeResponseJsonMatchesJsonPath('$.status');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data');

        foreach ($data['data']['stats'] as $item) {
            $this->assertArrayHasKey('team', $item);
            $this->assertArrayHasKey('player', $item);
            $this->assertArrayHasKey('id', $item['player']);
            $this->assertArrayHasKey('alias', $item['player']);
            $this->assertArrayHasKey('level', $item['player']);
            $this->assertArrayHasKey('isLive', $item['player']);

            $this->assertArrayHasKey('isLive', $item);

            $this->assertArrayHasKey('location', $item);
            $this->assertArrayHasKey('player', $item['location']);
            $this->assertArrayHasKey('lat', $item['location']);
            $this->assertArrayHasKey('lng', $item['location']);
            $this->assertArrayHasKey('game', $item['location']);

        }
    }

    public function testGetStats()
    {
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->sendGET('/api/stats/1');
        $this->checkStatList();
    }
}