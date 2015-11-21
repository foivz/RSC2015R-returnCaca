<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Player;
use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;

use Zantolov\AppBundle\Entity\User;

class LoadPlayersData extends AbstractDbFixture
{

    const NUMBER = 50;

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $userManager = $this->container->get('fos_user.user_manager');

        for ($i = 1; $i < self::NUMBER; $i++) {
            /** @var User $user */
            $user = $userManager->createUser();
            $user->setUsername($faker->userName);
            $user->setEmail($faker->email);
            $user->setPlainPassword('123456');
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_USER', 'ROLE_PLAYER'));
//            $user->setGcmRegistrationId($faker->uuid);
            $userManager->updateUser($user, true);

            $player = new Player();
            $player->setActive(true);
            $player->setAlias($faker->name);
            $player->setUser($user);
            $this->addReference('player' . $i, $player);
            $manager->persist($player);
        }


        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

}
