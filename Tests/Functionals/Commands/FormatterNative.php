<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application) {
    $application
        ->register('formatter:native')
            ->setDescription('Tests native formatter styles')
            ->setCode(function(InputInterface $input, OutputInterface $output) use ($application) {
                foreach (['info', 'comment', 'error', 'question'] as $style) {
                    $output->writeln(sprintf('<%1$s> %1$s text <%1$s>', $style));
                }

                $application->find('highlight')->run(
                    new ArrayInput(array(
                        'file' => __FILE__,
                        'lines' => range(15, 17)
                    )),
                    $output
                );
            });
};
