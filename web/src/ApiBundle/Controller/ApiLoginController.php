<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Refugee;
use AppBundle\Service\RoleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zantolov\AppBundle\Entity\ApiToken;


/**
 *
 * Login Controller
 *
 * @Route("/api")
 */
class ApiLoginController extends \Zantolov\AppBundle\Controller\API\ApiLoginController
{

    /**
     *
     * @Route("/login", name="api.login")
     * @Method("POST")
     */
    public function loginAction(Request $request)
    {
        /** @var JsonResponse $result */
        $result = parent::loginAction($request);

        $data = json_decode($result->getContent(), true);

        $cats = $this->getDoctrine()->getManager()->createQuery("SELECT c.id, c.name FROM ZantolovBlogBundle:Category c")->getScalarResult();
        $data['config'] = [
            'post_categories' => $cats,
        ];

        $result->setData($data);

        return $result;

    }

}