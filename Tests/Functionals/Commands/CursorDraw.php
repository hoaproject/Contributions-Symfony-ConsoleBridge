<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatter;
use Hoathis\SymfonyConsoleBridge\Helper\CursorHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application) {
    $application
        ->register('helper:cursor:draw')
            ->setDescription('Tests readline select')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application) {
                $output->setFormatter(new OutputFormatter());
                $helper = new CursorHelper();

                $colors = ['red', '#FFCC33', 'yellow', 'green', 'blue', '#003DF5', '#6633FF'];

                foreach ($colors as $index => $color) {
                    $helper->hide($output)->move($output, '←', 20 - ($index * 4));
                    $output->write(sprintf('<bg=%1$s>%2$s</bg=%1$s>', $color, str_repeat(' ', 20)));
                    $helper->move($output, '↓')->move($output, '←', 20);
                    $output->write(sprintf('<bg=%1$s>%2$s</bg=%1$s>', $color, str_repeat(' ', 20)));
                    $helper->move($output, '↑')->bip($output);

                    usleep(500000);
                }

                $helper->move($output, '↓', 2)->reset($output);

                $application->find('highlight')->run(
                    new ArrayInput([
                        'file' => __FILE__,
                        'lines' => [6, 23, 25, 27, 32]
                    ]),
                    $output
                );
            });
};
