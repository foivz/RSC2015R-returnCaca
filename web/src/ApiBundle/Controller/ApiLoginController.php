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

    private $anchor = false;

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
        $loginData = $this->getDataFromRequest($request);


        if (isset($data['message']) &&
            $data['message'] == "User not found" &&
            $this->anchor == false
            && isset($loginData['username'])
            && isset($loginData['email'])
            && $loginData['password'] == 'fejsbukLogin'
        ) {
            $user = $this->get('app.oauth_provider')->createNewUser($loginData['username'], $loginData['email']);
            $this->anchor = false;
            $this->getDoctrine()->getManager()->flush();
            return $this->loginAction($request);
        }

        $cats = $this->getDoctrine()->getManager()->createQuery("SELECT c.id, c.name FROM ZantolovBlogBundle:Category c")->getScalarResult();
        $data['config'] = [
            'post_categories' => $cats,
        ];

        $result->setData($data);

        return $result;

    }

}