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

    private function checkGenericLoginData()
    {
        $this->tester->seeResponseJsonMatchesJsonPath('$.status');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.username');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.email');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data.registrationId');
    }

    public function testSubmitStat()
    {


    }
}