<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Units\Formatter;

use atoum;
use Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatterStyle;
use Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatter as TestedClass;

class OutputFormatter extends atoum
{
    public function testClass()
    {
        $this
            ->testedClass
                ->isSubclassOf('Symfony\Component\Console\Formatter\OutputFormatterInterface')
        ;
    }

    public function testAddStyle()
    {
        $this
            ->given($name = uniqid())
            ->and($foreground = uniqid())
            ->and($background = uniqid())
            ->if($formatter = new TestedClass())
            ->then
                ->object($formatter->addStyle($name, $foreground, $background))->isIdenticalTo($formatter)
                ->object($formatter->getStyle($name))->isEqualTo(new OutputFormatterStyle($foreground, $background))
            ->if($options = array(uniqid(), uniqid()))
            ->then
                ->object($formatter->addStyle($name, $foreground, $background, $options))->isIdenticalTo($formatter)
                ->object($formatter->getStyle($name))->isEqualTo(new OutputFormatterStyle($foreground, $background, $options))
        ;
    }
}
