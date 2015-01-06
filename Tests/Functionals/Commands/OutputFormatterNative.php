<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, \Closure $highlight) {
    return $application
        ->register('output:formatter:native')
            ->setDescription('Tests native formatter styles')
            ->setCode(function(InputInterface $input, OutputInterface $output) use ($application, $highlight) {
                foreach (array('info', 'comment', 'error', 'question') as $style) {
                    $output->writeln(sprintf('<%1$s> %1$s text <%1$s>', $style));
                }

                $highlight(__FILE__, range(15, 17), $input, $output);
            });
};
