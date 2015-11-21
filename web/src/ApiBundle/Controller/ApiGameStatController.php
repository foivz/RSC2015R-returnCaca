<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Location;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Login Controller
 * @Route("/api")
 */
class ApiGameStatController extends \Zantolov\AppBundle\Controller\API\ApiLoginController
{

    /**
     *
     * @Route("/stats", name="api.player.stats.update")
     * @Method("POST")
     */
    public function postStatAction(Request $request)
    {
        $data = $this->getDataFromRequest($request);

        $playerId = @$data['playerId'];
        if (empty($playerId)) {
            /** @var Player $player */
            $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy(['user' => $this->getUser()]);
        } else {
            $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->find($playerId);
        }

        $player->setIsLive(isset($data['isLive']) ? $data['isLive'] : false);
        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Status submitted',
        ]);
    }


    /**
     * @Route("/stats/{gameId}", name="api.post.test")
     * @Method("GET")
     */
    public function statsAction($gameId)
    {
        /** @var Game $game */
        $game = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Game')->find($gameId);
        $stat = [];

        $getData = function (Player $player, $team) use ($game) {
            $playerStats = [
                'team'   => $team,
                'player' => $player,
                'isLive' => $player->isLive(),
            ];

            /** @var Location $location */
            $location = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Location')->findOneBy([
                'game'   => $game->getId(),
                'player' => $player->getId(),
            ], ['createdAt' => 'DESC']);

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

        return $this->createResponse([
            self::KEY_STATUS => self::STATUS_OK,
            self::KEY_DATA   => $stat,
        ]);
    }

    /**
     * @Route("/player-status", name="api.player.status")
     * @Method("GET")
     */
    public function playerStatusAction()
    {
        $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy(['user' => $this->getUser()]);
        return $this->createResponse([
            self::KEY_STATUS => self::STATUS_OK,
            self::KEY_DATA   => $player,
        ]);
    }

    /**
     * @Route("/game-status/{id}", name="api.game.status")
     * @Method("GET")
     */
    public function gameStatus($id)
    {
        /** @var Game $game */
        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Game')->find($id);
        return $this->createResponse([
            self::KEY_STATUS => self::STATUS_OK,
            self::KEY_DATA   => $game,
        ]);
    }


    /**
     * @Route("/capture-flag", name="api.flag.capture")
     * @Method("POST")
     */
    public function captureFlagAction(Request $request)
    {
        $data = $this->getDataFromRequest($request);
        if (empty($data)) {
            return $this->createResponse([
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'NO flag data',
            ]);
        }
        /** @var Player $player */
        $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy(['user' => $this->getUser()]);

        if (empty($player)) {
            return $this->createResponse([
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'NO player data found',
            ]);
        }

        /** @var Team $team */
        $team = $player->getTeam();
        $game = $team->getGame();

        $regionId = $data['regionId'];
        switch ($regionId) {
            case 1:
                $game->setOwnerRegion1($team);
                break;
            case 2:
                $game->setOwnerRegion2($team);
                break;
            case 3:
                $game->setOwnerRegion3($team);
                break;
            case 4:
                $game->setOwnerRegion4($team);
                break;
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Flag captured',
        ]);
    }

}