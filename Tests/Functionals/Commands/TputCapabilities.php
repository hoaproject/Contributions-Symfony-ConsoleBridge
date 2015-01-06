<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoathis\SymfonyConsoleBridge\Helper\PagerHelper;
use Hoathis\SymfonyConsoleBridge\Helper\ReadlineHelper;
use Hoathis\SymfonyConsoleBridge\Helper\TputHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, callable $highlight) {
    return $application
        ->register('helper:tput:capabilities')
            ->setDescription('Lists tput capabilities')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {

                $helper = new ReadlineHelper();
                $pager = new PagerHelper();

                $type = $helper->select(
                    $output,
                    'Select capabilities type: ',
                    ['strings', 'numbers', 'booleans']
                );

                $pager->less($output, function() use ($output, $type) {
                    $tput = new TputHelper();
                    $capabilities = $tput->getInformations();

                    foreach ($capabilities[$type] as $capability => $value) {
                        printf('%s: %s' . PHP_EOL, $capability, $value);
                    }
                });

                $highlight(__FILE__, array_merge(range(28, 33)), $input, $output);
            });

};
