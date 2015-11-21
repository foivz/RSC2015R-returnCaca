<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Game;
use AppBundle\Entity\Location;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use AppBundle\Helper\LatLngHelper;
use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;

use Zantolov\AppBundle\Entity\User;

class LoadLocationsData extends AbstractDbFixture
{
    const NUMBER = 100;

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($j = 1; $j < self::NUMBER; $j++) {
            $latLng = LatLngHelper::getRandomLatLngNear(46.308245, 16.337884);

            $location = new Location();
            $location->setPlayer($this->getReference('player' . (rand(2, LoadPlayersData::NUMBER) - 1)));
            $location->setGame($this->getReference('game' . (rand(2, LoadGamesData::NUMBER) - 1)));
            $location->setLat($latLng['lat']);
            $location->setLng($latLng['lng']);
            $manager->persist($location);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }

}