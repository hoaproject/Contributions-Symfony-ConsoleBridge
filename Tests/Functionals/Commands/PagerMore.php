<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Helper\PagerHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, callable $highlight) {
    return $application
        ->register('helper:pager:more')
            ->setDescription('Tests more pager')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {
                $helper = new PagerHelper();

                $helper->more(
                    $output,
                    function() {
                        passthru('cat ' . __DIR__ . '/*.php');
                    }
                );

                $highlight(__FILE__, array_merge([5, 15], range(17, 22)), $input, $output);
            });
};
