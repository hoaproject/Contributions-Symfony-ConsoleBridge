<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, callable $highlight) {
    return $application
        ->register('output:formatter:custom')
            ->setDescription('Tests custom formatter styles')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {
                $output->writeln('<fg=#FF00FF> fg=#FF00FF </fg=#FF00FF>');
                $output->writeln('<fg=#FF00FF;bg=white> fg=#FF00FF;bg=white </fg=#FF00FF;bg=white>');
                $output->writeln([
                    '<fg=#FF00FF;bg=white;options=inverse;options=underlined>',
                    'fg=#FF00FF;bg=white;options=inverse;options=underlined',
                    '</fg=#FF00FF;bg=white;options=inverse;options=underlined>'
                ]);

                $highlight(__FILE__, range(16, 22), $input, $output);
            });
};
