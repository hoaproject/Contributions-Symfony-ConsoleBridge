<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals\Commands;

use Hoa\Console\Readline\Autocompleter\Word;
use Hoathis\SymfonyConsoleBridge\Helper\PagerHelper;
use Hoathis\SymfonyConsoleBridge\Helper\ReadlineHelper;
use Hoathis\SymfonyConsoleBridge\Helper\TputHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function(Application $application, callable $highlight) {
    return $application
        ->register('helper:tput:echo')
            ->setDescription('Displays a capability value')
            ->setCode(function(InputInterface $input, OutputInterface $output) use($application, $highlight) {
                $tput = new TputHelper();
                $helper = new ReadlineHelper();

                $capability = $helper->autocomplete(
                    $output,
                    'Select a capability (TAB-TAB to autocomplete): ',
                    new Word(
                        array_keys(array_merge(
                            $tput->getInformations()['strings'],
                            $tput->getInformations()['numbers'],
                            $tput->getInformations()['booleans']
                        ))
                    )
                );

                $output->write($tput->get($capability));

                $highlight(__FILE__, array_merge([32]), $input, $output);
            });
};
