<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Units\Helper;

use atoum;
use Hoathis\SymfonyConsoleBridge\Helper\PagerHelper as TestedClass;

class PagerHelper extends atoum
{
    public function testClass()
    {
        $this
            ->testedClass
                ->isSubclassOf('Symfony\Component\Console\Helper\Helper')
            ->string(TestedClass::NAME)->isEqualTo('pager')
        ;
    }

    public function testGetName()
    {
        $this
            ->if($helper = new TestedClass())
            ->then
                ->string($helper->getName())->isEqualTo(TestedClass::NAME)
        ;
    }

    public function testLess()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($this->function->ob_start = function() { ob_start(); })
            ->and($this->function->ob_get_clean = function() { return ob_get_clean(); })
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->less($output, function() {}))->isIdenticalTo($helper)
                ->function('ob_start')->wasCalledWithArguments(null, null, null)->once()
                ->function('ob_get_clean')->wasCalledWithoutAnyArgument()->once()
                ->mock($output)
                    ->call('write')->never()
            ->given($this->resetFunction($this->function->ob_start))
            ->and($this->resetFunction($this->function->ob_get_clean))
            ->and($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->less($output, function() {}))->isIdenticalTo($helper)
                ->function('ob_start')->wasCalledWithArguments('Hoa\Console\Chrome\Pager::less')->once()
                ->function('ob_get_clean')->wasCalledWithoutAnyArgument()->once()
                ->mock($output)
                    ->call('write')->never()
            ->given($this->resetFunction($this->function->ob_start))
            ->and($this->resetFunction($this->function->ob_get_clean))
            ->if($code = function() use (& $buffer) { echo $buffer = uniqid(); })
            ->then
                ->object($helper->less($output, $code, true))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments($buffer)
                        ->after($this->function('ob_start')->wasCalledWithArguments('Hoa\Console\Chrome\Pager::less'))
                        ->after($this->function('ob_get_clean')->wasCalledWithoutAnyArgument())
                    ->once()
        ;
    }

    public function testMore()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($this->function->ob_start = function() { ob_start(); })
            ->and($this->function->ob_get_clean = function() { return ob_get_clean(); })
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->more($output, function() {}))->isIdenticalTo($helper)
                ->function('ob_start')->wasCalledWithArguments(null, null, null)->once()
                ->function('ob_get_clean')->wasCalledWithoutAnyArgument()->once()
                ->mock($output)
                    ->call('write')->never()
            ->given($this->resetFunction($this->function->ob_start))
            ->and($this->resetFunction($this->function->ob_get_clean))
            ->and($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->more($output, function() {}))->isIdenticalTo($helper)
                ->function('ob_start')->wasCalledWithArguments('Hoa\Console\Chrome\Pager::more')->once()
                ->function('ob_get_clean')->wasCalledWithoutAnyArgument()->once()
                ->mock($output)
                    ->call('write')->never()
            ->given($this->resetFunction($this->function->ob_start))
            ->and($this->resetFunction($this->function->ob_get_clean))
            ->if($code = function() use (& $buffer) { echo $buffer = uniqid(); })
            ->then
                ->object($helper->more($output, $code, true))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments($buffer)
                        ->after($this->function('ob_start')->wasCalledWithArguments('Hoa\Console\Chrome\Pager::more'))
                        ->after($this->function('ob_get_clean')->wasCalledWithoutAnyArgument())
                    ->once()
        ;
    }
}
