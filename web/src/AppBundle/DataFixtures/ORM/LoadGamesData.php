<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;

use Zantolov\AppBundle\Entity\User;

class LoadGamesData extends AbstractDbFixture
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($j = 1; $j < 20; $j++) {
            $team1 = new Team();
            $team1->setActive(true);
            $team1->setName($faker->word);

            $team2 = new Team();
            $team2->setActive(true);
            $team2->setName($faker->word);

            $teamIndex = rand(1, 49);
            $count = 0;
            for ($i = $teamIndex; $i < LoadPlayersData::NUMBER; $i = ($i++ % 49) + 1) {
                if ($count < 5) {
                    $team1->addPlayer($this->getReference('player' . $i));
                } elseif ($count < 10) {
                    $team2->addPlayer($this->getReference('player' . $i));
                } else {
                    break;
                }

                $count++;
            }

            $game = new Game();
            $game->setTeam1($team1);
            $game->setTeam2($team2);
            $game->setActive(true);

            $manager->persist($team1);
            $manager->persist($team2);
            $manager->persist($game);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }

}
