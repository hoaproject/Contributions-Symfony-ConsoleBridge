<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, callable $highlight) {
    return $application
        ->register('output:verbosity')
            ->setDescription('Tests verbosity levels')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {
                $output->writeln('<info>I\'m a decorated text only in the console</info>');

                if ($output->getVerbosity() === OutputInterface::VERBOSITY_NORMAL) {
                    $output->writeln('I\'ll be displayed with the <comment>normal</comment> verbosity level');
                }

                if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln('I\'ll be displayed with the <comment>verbose</comment> verbosity level');
                }

                if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERY_VERBOSE) {
                    $output->writeln('I\'ll be displayed with the <comment>very verbose</comment> verbosity level');
                }

                if ($output->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                    $output->writeln('I\'ll be displayed with the <comment>debug</comment> verbosity level');
                }


                $highlight(__FILE__, array_merge(range(16, 18), range(20, 22), range(24, 26), range(28, 30)), $input, $output);
            });
};
