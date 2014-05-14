<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Helper\PagerHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application) {
    $application
        ->register('pager:more')
            ->setDescription('Tests more pager')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application) {
                $helper = new PagerHelper();

                $helper->more(
                    $output,
                    function() {
                        passthru('cat ' . __DIR__ . '/*.php');
                    }
                );

                $application->find('highlight')->run(
                    new ArrayInput(array(
                        'file' => __FILE__,
                        'lines' => array_merge([5], range(18, 23))
                    )),
                    $output
                );
            });
};
