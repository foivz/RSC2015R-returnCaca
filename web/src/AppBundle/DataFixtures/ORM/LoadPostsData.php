<?php

namespace AppBundle\DataFixtures\ORM;

use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;
use Zantolov\BlogBundle\Entity\Post;
use Zantolov\MediaBundle\Entity\Image;

class LoadPostsData extends AbstractDbFixture
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {

            $p = new Post();
            $p->setActive(true);
            $p->setTitle($faker->sentence());
            $p->setAuthor($faker->name);
            $p->setBody($faker->realText(500));
            $p->setIntro($faker->realText(200));
            $p->setKeywords(implode(', ', $faker->words()));
            $p->setPublishedAt($faker->dateTimeBetween('-1 month', 'now'));


            for ($j = 1; $j < 50; $j++) {
                if (rand() % 7 == 0) {
                    $p->addCategory($this->getReference('c' . $j));
                }
            }

            $p->setImage($this->getReference('image' . rand(1, 19)));

            $manager->persist($p);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}
