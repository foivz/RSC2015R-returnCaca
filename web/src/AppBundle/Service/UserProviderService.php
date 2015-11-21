<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Security\EmailUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Zantolov\AppBundle\Entity\User;

class UserProviderService implements OAuthAwareUserProviderInterface, ContainerAwareInterface
{

    /** @var  ContainerInterface */
    private $container;

    /** @var  EmailUserProvider */
    private $fosService;

    /**
     * UserProviderService constructor.
     * @param EmailUserProvider $fosService
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        $this->fosService = $container->get('fos_user.user_provider.username_email');
    }


    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return EmailUserProvider
     */
    public function getFosService()
    {
        return $this->fosService;
    }

    /**
     * @param EmailUserProvider $fosService
     */
    public function setFosService($fosService)
    {
        $this->fosService = $fosService;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        try {
            $user = $this->fosService->loadUserByUsername($response->getUsername());
        } catch (UsernameNotFoundException $e) {

            $userManager = $this->container->get('fos_user.user_manager');

            /** @var User $user */
            $user = $userManager->createUser();
            $user->setUsername($response->getUsername());
            $user->setEmail($response->getEmail());
            $user->setPlainPassword(uniqid());
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_USER'));
            $userManager->updateUser($user, true);
        }

        return $user;
    }


}