<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application) {
    $application
        ->register('formatter:custom')
            ->setDescription('Tests custom formatter styles')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application) {
                $output->setFormatter(new OutputFormatter());

                $output->writeln('<fg=#FF00FF> fg=#FF00FF </fg=#FF00FF>');
                $output->writeln('<fg=#FF00FF;bg=white> fg=#FF00FF;bg=white </fg=#FF00FF;bg=white>');
                $output->writeln('<fg=#FF00FF;bg=white;options=inverse;options=underlined>
fg=#FF00FF;bg=white;options=inverse;options=underlined
</fg=#FF00FF;bg=white;options=inverse;options=underlined>');

                $application->find('highlight')->run(
                    new ArrayInput([
                        'file' => __FILE__,
                        'lines' => array_merge([5, 16], range(18, 22))
                    ]),
                    $output
                );
            });
};
