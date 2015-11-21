<?php

namespace ApiBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Location;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Login Controller
 *
 * @Route("/api")
 */
class ApiLocationController extends \Zantolov\AppBundle\Controller\API\ApiLoginController
{

    /**
     *
     * @Route("/locations", name="api.post.location")
     * @Method("POST")
     */
    public function postLocationAction(Request $request)
    {
        $data = $this->getDataFromRequest($request);
        $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy(['user' => $this->getUser()]);
        $location = new Location();
        $location->setLat($data['lat']);
        $location->setLng($data['lng']);

        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Game')->findOneBy(['id' => @$data['game']]);
        if (!empty($game)) {
            $location->setGame($game);
        }


        $location->setPlayer($player);
        $this->getDoctrine()->getManager()->persist($location);
        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse([
            self::KEY_STATUS  => self::STATUS_OK,
            self::KEY_MESSAGE => 'Location submitted',
        ]);
    }

    /**
     *
     * @Route("/locations", name="api.get.location")
     * @Method("GET")
     */
    public function getLocationsAction(Request $request)
    {
        $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy([
            'user' => $this->getUser()
        ]);

//        $locations = $this->getDoctrine()->getManager()->getRepository('AppBundle:Location')->findBy([
//            'player' => $player
//        ]);

        $locations = $this->getDoctrine()->getManager()->getRepository('AppBundle:Location')->findAll();

        return $this->createResponse([
            self::KEY_STATUS => self::STATUS_OK,
            self::KEY_DATA   => $locations,
        ]);
    }


}