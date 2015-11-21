<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\GameStat;
use AppBundle\Service\GameStatService;
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
     * @Route("/stats", name="api.post.stat")
     * @Method("POST")
     */
    public function postLocationAction(Request $request)
    {
        $data = $this->getDataFromRequest($request);
        $stat = new GameStat();

        $playerId = @$data['isLive'];
        if (empty($playerId)) {
            $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy(['user' => $this->getUser()]);
        } else {
            $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->find($playerId);
        }

        $stat->setPlayer($player);
        $stat->setIsLive(@$data['isLive']);

        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Game')->findOneBy(['id' => @$data['game']]);
        if (!empty($game)) {
            $stat->setGame($game);
        }

        $this->getDoctrine()->getManager()->persist($stat);
        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Location submitted',
        ]);
    }


    /**
     *
     * @Route("/stats/{gameId}", name="api.post.test")
     * @Method("GET")
     */
    public function testAction($gameId)
    {
        $data = [];
        /** @var GameStatService $service */
        $service = $this->get('game_stat');

        $stats = $service->getStatForGame($gameId);

        return $this->createResponse([
            self::KEY_STATUS => self::STATUS_OK,
            self::KEY_DATA   => $stats,
        ]);
    }

}