<?php

// src/AppBundle/Command/GreetCommand.php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zantolov:reload-data')
            ->setDescription('Load project specific demo data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $requiredFolders = [
            '/web/media',
            '/web/media/cache',
            '/web/uploads',
            '/web/uploads/images',
            '/web/uploads/images/default',
        ];

        $rootPath = realpath('');
        $output->writeln('Current path is: ' . $rootPath);

        foreach ($requiredFolders as $folderName) {
            $fullPath = $rootPath . $folderName;
            if (!is_dir($fullPath)) {
                mkdir($fullPath);
                chmod($fullPath, 777);
                $output->writeln(sprintf('Created dir: %s', $fullPath));
            }
        }

        $output->writeln(shell_exec('rm web/media/cache* -rf'));
        $output->writeln(shell_exec('rm web/uploads/images/default/* -rf'));
        $output->writeln(shell_exec('mkdir web/uploads/images/default -p'));
        $output->writeln(shell_exec('php app/console doctrine:schema:drop --force'));
        $output->writeln(shell_exec('php app/console doctrine:schema:create'));
        $output->writeln(shell_exec('php app/console doctrine:fixtures:load -n --fixtures=vendor/zantolov/mediabundle/src/DataFixtures/ORM --fixtures=src/AppBundle/DataFixtures/ORM'));
        $output->writeln(shell_exec('php app/console cache:clear'));
        /**
         * #php app/console doctrine:fixtures:load -n --fixtures=src/LovitBundle/DataFixtures/ORM
         */

    }
}