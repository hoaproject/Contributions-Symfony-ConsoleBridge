<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Helper\WindowHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, callable $highlight) {
    return $application
        ->register('helper:window:animate')
            ->setDescription('Tests less pager')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {
                $window = new WindowHelper();

                $output->writeln('<info>I\'m going to bring your window to the foreground and back to the foreground after one second</info>');
                sleep(1);
                $window->lower($output);
                sleep(1);
                $window->raise($output);

                $output->writeln('<info>I\'m going to minimize your window and restore it after one second</info>');
                sleep(1);
                $window->minimize($output);
                sleep(1);
                $window->restore($output);

                $highlight(__FILE__, array_merge([5, 15, 19, 21, 25, 27]), $input, $output);
            });
};
