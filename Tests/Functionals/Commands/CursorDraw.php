<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Helper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

return function(Application $application, callable $highlight) {
    return $application->register('helper:cursor:draw')
        ->setDescription('Tests readline select')
        ->setCode(function(Input\InputInterface $input, Output\OutputInterface $output) use($application, $highlight) {
            (new Helper\WindowHelper())->scroll($output, 'up', 2);
            $colors = ['red', '#FFCC33', 'yellow', 'green', 'blue', '#003DF5', '#6633FF'];

            $helper = new Helper\CursorHelper();
            $helper->hide($output)->move($output, 'up', 1);

            foreach ($colors as $index => $color) {
                $helper->move($output, 'left', 20 - ($index * 4));
                $output->write(sprintf('<bg=%1$s>%2$s</bg=%1$s>', $color, str_repeat(' ', 20)));
                $helper->move($output, 'down')->move($output, 'left', 20);
                $output->write(sprintf('<bg=%1$s>%2$s</bg=%1$s>', $color, str_repeat(' ', 20)));
                $helper->move($output, 'up')->bip($output);

                usleep(250000);
            }

            $helper
                ->move($output, 'down', 2)
                ->move($output, 'left', 100)
                ->reset($output)
                ->show($output);

            $highlight(__FILE__, range(17, 34), $input, $output);
        });
};
