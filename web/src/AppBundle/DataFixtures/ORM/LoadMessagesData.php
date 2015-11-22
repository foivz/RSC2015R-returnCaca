<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Game;
use AppBundle\Entity\Location;
use AppBundle\Entity\Message;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use AppBundle\Helper\LatLngHelper;
use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;

use Zantolov\AppBundle\Entity\User;

class LoadMessagesData extends AbstractDbFixture
{
    const NUMBER = 200;

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($j = 1; $j <= self::NUMBER; $j++) {
            $message = new Message();
            $message->setMessage($faker->sentence());
            $message->setPlayer($this->getReference('player' . rand(1, 10)));
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }

}
