<?php

namespace AppBundle\Service;

use AppBundle\Entity\Refugee;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zantolov\AppBundle\Entity\User;

class RoleService implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $c)
    {
        $this->setContainer($c);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function getUserRole(User $user)
    {
        $return = [
            'profile_refugee'   => null,
            'profile_citizen'   => null,
            'profile_authority' => null,
        ];


        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        // Is it a refugee?
        $refugee = $em->getRepository('AppBundle:Refugee')->findOneBy(['user' => $user->getId()]);
        if (!empty($refugee)) {
            $return['profile_refugee'] = $refugee;
        }

        // Is it a authority?
        $auth = $em->getRepository('AppBundle:Authority')->findOneBy(['user' => $user->getId()]);
        if (!empty($auth)) {
            $return['profile_authority'] = $auth;
        }

        // Is it a citizen?
        $citizen = $em->getRepository('AppBundle:Citizen')->findOneBy(['user' => $user->getId()]);
        if (!empty($citizen)) {
            $return['profile_citizen'] = $citizen;
        }

        return $return;
    }

    /**
     * @param $profile
     * @return string
     */
    public function getRoleType($profile)
    {
        if ($profile instanceof Refugee) {
            return 'refugee';
        }

        return null;
    }

}