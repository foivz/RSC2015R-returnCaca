<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;

use Zantolov\AppBundle\Entity\User;
use DateTime;
use DateInterval;

class LoadGamesData extends AbstractDbFixture
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $userManager = $this->container->get('fos_user.user_manager');

        $addPlayer = function ($i, $team) use ($manager, $userManager, $faker) {
            /** @var User $user */
            $user = $userManager->createUser();
            $user->setUsername('player' . $i);
            $user->setEmail('player' . $i . '@mailinator.com');
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
            $player->setImage($this->getReference('image' . rand(1, 19)));

            $manager->persist($player);
            $team->addPlayer($player);
        };

        $team1 = new Team();
        $team1->setActive(true);
        $team1->setName($faker->word);
        $manager->persist($team1);

        for ($i = 1; $i <= 5; $i++) {
            $addPlayer($i, $team1);
        }

        $team2 = new Team();
        $team2->setActive(true);
        $team2->setName($faker->word);
        $manager->persist($team2);

        for ($i = 6; $i <= 10; $i++) {
            $addPlayer($i, $team2);
        }

        $game = new Game();
        $game->setTeam1($team1);
        $game->setTeam2($team2);
        $game->setActive(true);
        $endTime = new DateTime();
        $endTime->add(new DateInterval('PT15M'));
        $game->setEndTimeStamp($endTime);
        $this->addReference('game', $game);

        $manager->persist($team1);
        $manager->persist($team2);

        $queue = new Team();
        $queue->setActive(true);
        $queue->setName('queue');
        $manager->persist($queue);

        $manager->persist($game);


        for ($i = 1; $i < 10; $i++) {
            $team = new Team();
            $team->setName($faker->word);
            $team->setActive(true);
            $team->setPoints(rand(10, 400));
            $manager->persist($team);
        }

        for ($i = 11; $i < 20; $i++) {
            /** @var User $user */
            $user = $userManager->createUser();
            $user->setUsername('player' . $i);
            $user->setEmail('player' . $i . '@mailinator.com');
            $user->setPlainPassword('123456');
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_USER', 'ROLE_PLAYER'));
            $userManager->updateUser($user, true);

            $player = new Player();
            $player->setActive(true);
            $player->setAlias($faker->name);
            $player->setUser($user);
            $this->addReference('player' . $i, $player);
            $player->setImage($this->getReference('image' . rand(1, 19)));

            $manager->persist($player);
        }


        $manager->flush();

    }

    public function getOrder()
    {
        return 4;
    }

}
