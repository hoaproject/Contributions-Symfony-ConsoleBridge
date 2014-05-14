<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals;

use Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatter;
use Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatterStyle;
use Hoathis\SymfonyConsoleBridge\Output\ConsoleOutput;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__ . '/../../vendor/autoload.php';

$application = new Application('Hoathis\SymfonyConsoleBridge');
$input = new ArgvInput();
$output = new ConsoleOutput();


$application
    ->register('highlight')
        ->setDescription('Highlights PHP code')
        ->addArgument('file')
        ->addArgument('lines', InputArgument::OPTIONAL | InputArgument::IS_ARRAY)
        ->setCode(function(InputInterface $input, OutputInterface $output) {
            $output->getFormatter()->setStyle('num', new OutputFormatterStyle('#e4cbf4', '#795290', ['b']));
            $output->getFormatter()->setStyle('numhl', new OutputFormatterStyle('#795290', '#e4cbf4', ['b']));
            $output->getFormatter()->setStyle('code', new OutputFormatterStyle('#795290', '#e4cbf4'));
            $output->getFormatter()->setStyle('codehl', new OutputFormatterStyle('#795290', '#FFFFFF', ['b']));
            $output->getFormatter()->setStyle('file', new OutputFormatterStyle('#e4cbf4', '#795290'));

            $file = $input->getArgument('file');
            $hl = $input->getArgument('lines');
            $lines = file($file);
            $max = strlen($file);

            foreach ($lines as $line) {
                $max = strlen($line) > $max ? strlen($line) : $max;
            }

            $output->write([
                PHP_EOL,
                '<num>     </num>',
                sprintf('<file> %s </file>%s', str_repeat(' ', $max), PHP_EOL),
                '<num>     </num>',
                sprintf('<file> %s </file>%s', str_pad($file, $max, ' ', STR_PAD_RIGHT), PHP_EOL),
                '<num>     </num>',
                sprintf('<file> %s </file>%s', str_repeat(' ', $max), PHP_EOL)
            ]);

            foreach ($lines as $num => $line) {
                $trimmed = rtrim($line);
                $highlight = in_array($num + 1, $hl);

                $output->write([
                    sprintf('<%1$s> %2$3d </%1$s>', $highlight === true ? 'numhl' : 'num', $num + 1),
                ]);

                $pattern = $output->getFormatter()->format(sprintf('<%1$s> %%s </%1$s>%%s', $highlight === true ? 'codehl' : 'code'));

                $decorated = $output->getFormatter()->isDecorated();
                $output->getFormatter()->setDecorated(false);
                $output->write([
                    sprintf(
                        $pattern,
                        OutputFormatter::escape(str_pad(
                            $trimmed,
                            $max,
                            ' ',
                            STR_PAD_RIGHT
                        )),
                        $trimmed !== $line ? PHP_EOL : ''
                    )
                ]);
                $output->getFormatter()->setDecorated($decorated);
            }
        });

foreach (new \RecursiveDirectoryIterator(__DIR__ . '/Commands/', \FilesystemIterator::SKIP_DOTS) as $file) {
    $register = include_once $file;

    $register($application);
}

$application->run($input, $output);
