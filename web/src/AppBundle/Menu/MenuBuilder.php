<?php

namespace AppBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Zantolov\AppBundle\Menu\MenuBuilderInterface;

class MenuBuilder implements MenuBuilderInterface
{

    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMenu(RequestStack $requestStack)
    {
        $menuItems = [];
        $menuItems['paintball'] = $this->factory->createItem('Paintball', array('label' => 'Paintball'))
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'fa fa-star');

        $menuItems['paintball']->addChild('Players', array('route' => 'admin_players.index'))->setAttribute('icon', 'fa fa-user');
        $menuItems['paintball']->addChild('Teams', array('route' => 'admin_teams.index'))->setAttribute('icon', 'fa fa-users');
        $menuItems['paintball']->addChild('Games', array('route' => 'admin_games.index'))->setAttribute('icon', 'fa fa-folder');

        return $menuItems;
    }


    public function getOrder()
    {
        return 0;
    }
}