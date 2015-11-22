<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Service\GcmService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Zantolov\AppBundle\Entity\User;

/**
 * @Route("/api")
 */
class ApiCommunicationController extends \Zantolov\AppBundle\Controller\API\ApiLoginController
{
    /**
     *
     * @Route("/msg/{gameId}", name="api.post.msg")
     * @Method("POST")
     */
    public function postMsgAction(Request $request, $gameId)
    {

        $user = $this->getUser();
        if (empty($user) || !($user instanceof User)) {
            return $this->createResponse([
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'Not logged in',
            ]);
        }

        $msg = $request->get('message');

        if (empty($msg)) {
            return $this->createResponse([
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'NO message provided',
            ]);
        }

        /** @var Game $game */
        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy($gameId);

        /** @var Player $player */
        $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy([
            'user' => $user
        ]);

        $currentTeam = null;

        if ($game->getTeam1()->getPlayers()->contains($player)) {
            $currentTeam = $game->getTeam1();
        } elseif ($game->getTeam2()->getPlayers()->contains($player)) {
            $currentTeam = $game->getTeam2();
        }

        $teamMembers = $currentTeam->getPlayers();


        $ids = [];
        /** @var Player $teamMember */
        foreach ($teamMembers as $teamMember) {
            if ($teamMember->getUser() != $this->getUser()) {
                $ids[] = $teamMember->getUser()->getRegistrationId();
            }
        }

        /** @var GcmService $gcmService */
        $gcmService = $this->get('gcm_service');
        $gcmService->sendNotification($ids, [
            'message' => $msg,
            'from'    => $player,
            'team'    => $currentTeam,
        ]);

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Message sent',
        ]);

    }

}