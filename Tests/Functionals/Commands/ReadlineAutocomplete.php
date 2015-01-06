<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoa\Console\Readline\Autocompleter\Path;
use Hoathis\SymfonyConsoleBridge\Helper\ReadlineHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, \Closure $highlight) {
    return $application
        ->register('helper:readline:autocomplete')
            ->setDescription('Tests readline select')
            ->addOption('multi', null, InputOption::VALUE_NONE)
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {
                $helper = new ReadlineHelper();

                $path = $helper->autocomplete(
                    $output,
                    'Select a directory: ',
                    new Path(),
                    function($input) {
                        return is_dir($input);
                    }
                );

                $output->writeln(sprintf('<info>You selected</info>: %s', $path));

                $highlight(__FILE__, array(20, 27), $input, $output);
            });
};
