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
        $menuItems = array();

        return $menuItems;
    }


    public function getOrder()
    {
        return 5;
    }
}