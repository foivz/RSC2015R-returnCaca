<?php

namespace AppBundle\DataFixtures\ORM;

use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;
use Zantolov\BlogBundle\Entity\Category;
use Zantolov\BlogBundle\Entity\Post;
use Zantolov\MediaBundle\Entity\Image;

class LoadCategoriesData extends AbstractDbFixture
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 8; $i++) {

            $c = new Category();
            $c->setName($faker->word);
            $c->setActive(true);
            $manager->persist($c);

            $this->addReference('c' . $i, $c);
        }

        for ($i = 8; $i < 50; $i++) {

            $c = new Category();
            $c->setName($faker->word);
            $c->setActive(true);
            $c->setParent($this->getReference('c' . rand(1, 7)));
            $manager->persist($c);

            $this->addReference('c' . $i, $c);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}
