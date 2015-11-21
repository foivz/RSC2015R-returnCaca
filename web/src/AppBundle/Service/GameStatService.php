<?php

namespace AppBundle\Service;

use AppBundle\Entity\Game;
use AppBundle\Entity\GameStat;
use AppBundle\Entity\Location;
use AppBundle\Entity\Player;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GameStatService implements ContainerAwareInterface
{

    /** @var  ContainerInterface */
    private $container;


    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * UserProviderService constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    public function getStatForGame($gameId)
    {
        /** @var Game $game */
        $game = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Game')->find($gameId);
        if (empty($game)) {
            return null;
        }

        $stat = [];

        $getData = function (Player $player, $team) use ($game) {
            $playerStats = [
                'team'   => $team,
                'player' => $player,
                'isLive' => 1,
            ];

            /** @var GameStat $stat */
            $stat = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:GameStat')->findBy([
                'game'   => $game->getId(),
                'player' => $player->getId(),
            ]);

            /** @var Location $location */
            $location = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Location')->findOneBy([
                'game'   => $game->getId(),
                'player' => $player->getId(),
            ], ['createdAt' => 'DESC']);

            if (!empty($stat)) {
                if (!$stat->isIsLive()) {
                    $playerStats['isLive'] = 0;
                }
            }

            $playerStats['location'] = $location;
            return $playerStats;
        };


        /** @var Player $player */
        foreach ($game->getTeam1()->getPlayers() as $player) {
            $stat[] = $getData($player, 1);
        }

        /** @var Player $player */
        foreach ($game->getTeam2()->getPlayers() as $player) {
            $stat[] = $getData($player, 2);
        }



        return ['stats' => $stat, 'game' => $game];

    }

}