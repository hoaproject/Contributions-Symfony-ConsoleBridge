<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals;

use Hoathis\SymfonyConsoleBridge;
use Hoathis\SymfonyConsoleBridge\Formatter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

require_once __DIR__ . '/../../vendor/autoload.php';

$application = new Application('Hoathis\SymfonyConsoleBridge');
$application
    ->register('highlight')
        ->setDescription('Highlights PHP code')
        ->addArgument('file', Input\InputArgument::REQUIRED)
        ->addArgument('lines', Input\InputArgument::OPTIONAL | Input\InputArgument::IS_ARRAY)
        ->setCode(function(Input\InputInterface $input, Output\OutputInterface $output) {
            $formatter = new Highlighter(file_get_contents($input->getArgument('file')));
            $formatter->highlight($input->getArgument('lines'));

            $output->write(array(PHP_EOL, Formatter\OutputFormatter::escape($formatter->format())));
        });

foreach (new \RecursiveDirectoryIterator(__DIR__ . '/Commands/', \FilesystemIterator::SKIP_DOTS) as $file) {
    $register = include_once $file;

    $highlight = function($file, array $highlights, Input\InputInterface $input, Output\OutputInterface $output) use($application) {
        if ($input->getOption('no-code') === false && $output->isDecorated()) {
            $application->find('highlight')->run(
                new Input\ArrayInput(array(
                    'file' => $file,
                    'lines' => $highlights
                )),
                $output
            );
        }
    };

    $command = $register($application, $highlight);
    $command->addOption('no-code', null, Input\InputOption::VALUE_NONE, 'Do not display code snippets');
}

$output = new SymfonyConsoleBridge\Output\ConsoleOutput();
$output->setFormatter(new Formatter\OutputFormatter());

$application->run(new Input\ArgvInput(), $output);
