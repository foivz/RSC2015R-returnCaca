<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Message;
use AppBundle\Entity\Player;
use AppBundle\Service\GcmService;
use Doctrine\ORM\EntityRepository;
use Faker\Provider\tr_TR\DateTime;
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
     * @Route("/msg", name="api.post.msg")
     * @Method("POST")
     */
    public function postMsgAction(Request $request)
    {
        $user = $this->getUser();
        if (empty($user) || !($user instanceof User)) {
            return $this->createResponse([
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'Not logged in',
            ]);
        }

        $data = $this->getDataFromRequest($request);
        $msg = $data['message'];

        if (empty($msg)) {
            return $this->createResponse([
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'NO message provided',
            ]);
        }

        /** @var Game $game */
        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->find(1);

        /** @var Player $player */
        $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy([
            'user' => $user
        ]);

        $teamMembers = $player->getTeam()->getPlayers();

        $ids = [];
        /** @var Player $teamMember */
        foreach ($teamMembers as $teamMember) {
//            if ($teamMember->getUser() != $this->getUser()) {
                $id = $teamMember->getUser()->getGcmRegistrationId();
                if (!empty($id)) {
                    $ids[] = $id;
                }
//            }
        }

        $message = new Message();
        $message->setMessage($msg);
        $message->setPlayer($player);
        $this->getDoctrine()->getManager()->persist($message);
        $this->getDoctrine()->getManager()->flush();

        /** @var GcmService $gcmService */
        $gcmService = $this->get('gcm_service');
        $gcmService->sendNotification($ids, [
            'message' => $msg,
            'from'    => $player,
            'team'    => $teamMember,
        ]);

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Message sent',
        ]);
    }


    /**
     *
     * @Route("/msg", name="api.get.msg")
     * @Method("GET")
     */
    public function getMessagesAction()
    {
        /** @var EntityRepository $repo */
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Message');

        $dt = new \DateTime();
        $dt = $dt->sub(new \DateInterval('PT5S'));

        $builder = $repo->createQueryBuilder('m')
            ->where('m.createdAt > :time')
            ->setParameter('time', $dt)->orderBy('m.createdAt', 'DESC');

        $data = $builder->getQuery()->getResult();

        return $this->createResponse([
            self::KEY_STATUS => self::STATUS_OK,
            self::KEY_DATA   => $data,
        ]);
    }


}