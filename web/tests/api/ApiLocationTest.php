<?php

require_once __DIR__ . '/TestLoginTrait.php';

class ApiLocationTest extends \Codeception\TestCase\Test
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

    public function testSubmitLocation()
    {
        $token = $this->doLogin('player1@mailinator.com', '123456');

        $latLng = AppBundle\Helper\LatLngHelper::getRandomLatLngNear(46.308245, 16.337884);
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->haveHttpHeader('X-Api-token', $token);
        $this->tester->sendPOST('/api/locations', json_encode([
            'lat' => $latLng['lat'],
            'lng' => $latLng['lng'],
        ]));

        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->tester->seeResponseJsonMatchesJsonPath('$.status');
        $this->tester->seeResponseJsonMatchesJsonPath('$.message');

    }

    private function checkLocationList()
    {
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->tester->seeResponseJsonMatchesJsonPath('$.status');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data');

        $this->tester->seeResponseJsonMatchesJsonPath('$.data[0].lat');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data[0].lng');
        $this->tester->seeResponseJsonMatchesJsonPath('$.data[0].game');
    }

    public function testGetLocations()
    {
        $token = $this->doLogin('player1@mailinator.com', '123456');
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->haveHttpHeader('X-Api-token', $token);
        $this->tester->sendGET('/api/locations');
        $this->checkLocationList();
    }


    public function testGetLocationsWithFilters()
    {
        $token = $this->doLogin('player1@mailinator.com', '123456');
        $this->tester->haveHttpHeader('Content-type', 'application/json');
        $this->tester->haveHttpHeader('X-Api-token', $token);
        $filter = ['game' => rand(1, \AppBundle\DataFixtures\ORM\LoadGamesData::NUMBER - 1)];
        $this->tester->sendGET('/api/locations', $filter);
        $this->checkLocationList();

        $data = json_decode($this->tester->grabResponse(), true);
        foreach ($data['data'] as $item) {
            $this->assertEquals($filter['game'], $item['game']);
        }
    }
}