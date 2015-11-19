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
        $token = $data['data']['token'];

        /** @var ApiToken $token */
        $token = $this->getDoctrine()->getRepository('ZantolovAppBundle:ApiToken')->findOneBy(['token' => $token]);
        $user = $token->getUser();

        /** @var RoleService $roleService */
        $roleService = $this->get('role_service');
        $profiles = $roleService->getUserRole($user);

        foreach ($profiles as $profileName => &$profile) {
            if (!empty($profile)) {
                $profile = $profile->jsonSerialize();
                $profile['url'] = $this->generateUrl('site.profile.check', ['id' => $profile['id']], UrlGeneratorInterface::ABSOLUTE_URL);
                $profile['qr'] = 'https://chart.googleapis.com/chart?cht=qr&chs=400x400&chl=' . $profile['url'];
            }
            $data[$profileName] = $profile;
        }

        $cats = $this->getDoctrine()->getManager()->createQuery("SELECT c.id, c.name FROM ZantolovBlogBundle:Category c")->getScalarResult();
        $data['config'] = [
            'post_categories' => $cats,
        ];

        $result->setData($data);

        return $result;

    }

}