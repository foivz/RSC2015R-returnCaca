<?php

namespace AppBundle\Service;

use AppBundle\Entity\Player;
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
use Zantolov\MediaBundle\Entity\Image;

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
            $user = $this->createNewUser($response->getUsername(), $response->getEmail(), $response);
        }

        return $user;
    }

    public function createNewUser($token, $email, UserResponseInterface $response = null)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        /** @var User $user */
        $user = $userManager->createUser();
        $user->setUsername($token);
        $user->setEmail($email);
        $user->setPlainPassword('fejsbukLogin');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_USER'));
        $userManager->updateUser($user, true);

        $player = new Player();


        if (!is_null($response)) {
            $image = $response->getProfilePicture();

            $img = new Image();
            $img->setActive(true);
            $name = time() . '.jpg';
            $img->setImageName($name);

            $imgFile = realpath(__DIR__ . '/../../../web/uploads/images/default/') . '/' . $name;
            file_put_contents($imgFile, file_get_contents($image));
            $player->setImage($img);

            $player->setAlias($response->getRealName());
        } else {
            $player->setAlias($email);

        }

        $player->setActive(true);
        $player->setUser($user);

        $this->container->get('doctrine')->getManager()->persist($img);
        $this->container->get('doctrine')->getManager()->persist($player);
        $this->container->get('doctrine')->getManager()->flush();

        return $user;


    }


}