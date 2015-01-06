<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoa\Console\Readline\Autocompleter\Word;
use Hoathis\SymfonyConsoleBridge\Helper\PagerHelper;
use Hoathis\SymfonyConsoleBridge\Helper\ReadlineHelper;
use Hoathis\SymfonyConsoleBridge\Helper\TputHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, \Closure $highlight) {
    return $application
        ->register('helper:tput:get')
            ->setDescription('Returns a tput capability')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {
                $tput = new TputHelper();
                $helper = new ReadlineHelper();

                $capabilities = $tput->getInformations();
                $capability = $helper->autocomplete(
                    $output,
                    'Select a capability (TAB-TAB to autocomplete): ',
                    new Word(
                        array_keys(array_merge(
                            $capabilities['strings'],
                            $capabilities['numbers'],
                            $capabilities['booleans']
                        ))
                    )
                );

                $output->writeln(sprintf('<info>%s</info>: %s', $capability, var_export($tput->get($capability), true)));

                $highlight(__FILE__, array(18, 21, 34), $input, $output);
            });
};
