<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Helper\ReadlineHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, \Closure $highlight) {
    return $application
        ->register('helper:readline:select')
            ->setDescription('Tests readline select')
            ->addOption('multi', null, InputOption::VALUE_NONE)
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {
                $helper = new ReadlineHelper();

                $selection = (array) $helper->select(
                    $output,
                    $input->getOption('multi') ? 'Select some values: ' : 'Select a value: ',
                    array(
                        '<info>php</info>' => ReadlineHelper::SEPARATOR,
                        'hoa', 'symfony', 'laravel',
                        '<info>js</info>' => ReadlineHelper::SEPARATOR,
                        'express', 'connect', 'restify',
                    ),
                    null,
                    false,
                    $input->getOption('multi')
                );

                $output->writeln(sprintf('<info>You selected</info>: %s', implode(', ', $selection)));

                $highlight(__FILE__, array_merge(array(5, 17), range(19, 31)), $input, $output);
            });
};
