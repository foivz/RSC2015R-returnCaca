<?php

// src/AppBundle/Command/GreetCommand.php
namespace AppBundle\Command;

use AppBundle\Entity\Game;
use AppBundle\Entity\Location;
use AppBundle\Entity\Player;
use AppBundle\Helper\LatLngHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateLocationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zantolov:update-locations')
            ->setDescription('Update locations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var Game $game */
        $game = $this->getContainer()->get('doctrine')->getManager()->getRepository('AppBundle:Game')->find(1);
        if (empty($game)) {
            return null;
        }

        /** @var EntityManager $manager */
        $manager = $this->getContainer()->get('doctrine')->getManager();

        $getData = function ($player) use ($manager, $game) {

            /** @var Location $oldLocation */
            $oldLocation = $manager->getRepository('AppBundle:Location')->findOneBy(
                ['player' => $player], ['createdAt' => 'DESC']
            );

            if (!empty($oldLocation)) {
                $latLng = [
                    'lat' => $oldLocation->getLat() + rand(1, 9) / 1000000 * (((rand() % 2 == 0) ? 1 : -1)),
                    'lng' => $oldLocation->getLng() + rand(1, 9) / 1000000 * (((rand() % 2 == 0) ? 1 : -1)),
                ];
            } else {
                $latLng = LatLngHelper::getRandomLatLngNear(46.306390, 16.339145);
            }

            $location = new Location();
            $location->setPlayer($player);
            $location->setGame($game);
            $location->setLat($latLng['lat']);
            $location->setLng($latLng['lng']);
            $manager->persist($location);
        };

        while (true) {
            /** @var Player $player */
            foreach ($game->getTeam1()->getPlayers() as $player) {
                $stat[] = $getData($player);
            }

            /** @var Player $player */
            foreach ($game->getTeam2()->getPlayers() as $player) {
                $stat[] = $getData($player);
            }

            $manager->flush();
            $output->writeln('Updated');
            sleep(1);
        }

    }
}