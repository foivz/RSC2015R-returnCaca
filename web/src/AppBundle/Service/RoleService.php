<?php

namespace AppBundle\Service;

use AppBundle\Entity\Player;
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


    public function getPlayerByUser(User $user)
    {
        /** @var Player $player */
        $player = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Player')->findOneBy([
            'user' => $user
        ]);

        return $player;

    }

    public function getTeam($player, $game)
    {


    }

}