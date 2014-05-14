<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Helper\ReadlineHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application) {
    $application
        ->register('helper:readline:select:multi')
            ->setDescription('Tests readline select multi')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application) {
                $helper = new ReadlineHelper();

                $selection = $helper->select(
                    $output,
                    'Select a value:',
                    [
                        '<info>php</info>' => ReadlineHelper::SEPARATOR,
                        'hoa', 'symfony', 'laravel',
                        '<info>js</info>' => ReadlineHelper::SEPARATOR,
                        'express', 'connect', 'restify',
                    ],
                    null,
                    false,
                    true
                );

                $output->writeln(sprintf('<info>You selected</info>: %s', implode(', ', $selection)));

                $application->find('highlight')->run(
                    new ArrayInput([
                        'file' => __FILE__,
                        'lines' => array_merge([5, 16], range(18, 30))
                    ]),
                    $output
                );
            });
};
