<?php

namespace ApiBundle\Controller;


use AppBundle\Entity\Game;
use AppBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Login Controller
 *
 * @Route("/api")
 */
class ApiNewGameController extends \Zantolov\AppBundle\Controller\API\ApiLoginController
{

    private function getQueue()
    {
        $q = $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findOneBy(['name' => 'queue']);

        return $q;
    }

    /**
     * @Route("/queue/join", name="api.queue.join")
     * @Method("POST")
     */
    public function postJoinAction(Request $request)
    {
        $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy(['user' => $this->getUser()]);

        /** @var Team $queue */
        $queue = $this->getQueue();

        $queue->addPlayer($player);
        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Joined',
        ]);
    }

    /**
     * @Route("/team/join", name="api.team.join")
     * @Method("POST")
     */
    public function postTeamJoinAction(Request $request)
    {
        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Game')->find(1);
        /** @var Team $team */

        $teamId = $request->get('team');
        if ($teamId == 0) {
            $team = $this->getQueue();
        } else {
            $team = $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->find($teamId);
        }


        $players = $request->get('players');
        if (is_array($players)) {
            foreach ($players as $playerId) {
                $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->find($playerId);
                if (!empty($player)) {
                    $team->addPlayer($player);
                }
            }
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Joined',
        ]);
    }

    /**
     * @Route("/queue", name="api.get.queue")
     * @Method("GET")
     */
    public function getLocationsAction(Request $request)
    {
        return $this->createResponse([
            self::KEY_STATUS => self::STATUS_OK,
            self::KEY_DATA   => $this->getQueue(),
        ]);
    }

    /**
     * @Route("/team/members/", name="api.team.get")
     * @Method("GET")
     */
    public function getTeamMembersAction(Request $request)
    {
        $team = $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->find($request->get('teamId'));
        if(empty($team)){
            $team = new Team();
        }

        return $this->createResponse([
            self::KEY_STATUS => self::STATUS_OK,
            self::KEY_DATA   => $team,
        ]);
    }


    /**
     * @Route("/game/setup", name="api.game.setup")
     * @Method("POST")
     */
    public function setupGameAction(Request $request)
    {
        /** @var Game $game */
        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Game')->find(1);

        $duration = $request->get('duration');
        $killPoints = $request->get('killPoints');
        $flagPoints = $request->get('flagPoints');

        $game->setDuration($duration);
        $game->setKillPoints($killPoints);
        $game->setFlagPoints($flagPoints);

        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Updated',
        ]);
    }

}