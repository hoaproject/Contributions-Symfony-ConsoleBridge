<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Units\Helper;

use atoum;
use Hoa\Console\Tput;
use Hoathis\SymfonyConsoleBridge\Helper\CursorHelper as TestedClass;

class CursorHelper extends atoum
{
    public function beforeTestMethod($method)
    {
        if (false === defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'r'));
        }
    }

    public function testClass()
    {
        $this
            ->testedClass
                ->isSubclassOf('Symfony\Component\Console\Helper\Helper')
            ->string(TestedClass::NAME)->isEqualTo('cursor')
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

    public function testMove()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->move($output, $steps = uniqid()))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->move($output, TestedClass::MOVE_RIGHT . ' ' . TestedClass::MOVE_RIGHT . ' ' . TestedClass::MOVE_RIGHT))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[1C\033[1C\033[1C")->once()
                ->object($helper->move($output, TestedClass::MOVE_RIGHT, 3))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[3C")->once()
                ->object($helper->move($output, array(TestedClass::MOVE_UP, TestedClass::MOVE_RIGHT, TestedClass::MOVE_DOWN, TestedClass::MOVE_LEFT)))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[1A\033[1C\033[1B\033[1D")->once()
        ;
    }

    public function testMoveTo()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->moveTo($output, rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX)))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->moveTo($output, $x = rand(0, PHP_INT_MAX), $y = rand(0, PHP_INT_MAX)))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[" . $y . ';' . $x .'H')->once()
        ;
    }

    public function testClear()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->given($tput = new Tput())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->clear($output, TestedClass::CLEAR_SCREEN))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->clear($output, TestedClass::CLEAR_SCREEN))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->atLeastOnce()
                ->object($helper->clear($output, array(TestedClass::CLEAR_ABOVE, TestedClass::CLEAR_RIGHT, TestedClass::CLEAR_BELOW, TestedClass::CLEAR_LEFT)))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->atLeastOnce()
        ;
    }

    public function testHide()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->hide($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->hide($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[?25l")->once()
        ;
    }

    public function testShow()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->show($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->show($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->atLeastOnce()
        ;
    }

    public function testColorize()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->colorize($output, 'u fg(red) bg(white)'))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->colorize($output, 'u bg(red) fg(white)'))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[4;41;37m")->once()
                ->object($helper->colorize($output, array('b', 'bg(white)', 'fg(red)')))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[1;47;31m")->once()
        ;
    }

    public function testReset()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->reset($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->reset($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[0;39;49m")->once()
        ;
    }

    public function testStyle()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->style($output, TestedClass::STYLE_BLOCK))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->style($output, TestedClass::STYLE_VERTICAL))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[5 q")->once()
                ->object($helper->style($output, TestedClass::STYLE_VERTICAL, false))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\033[6 q")->once()
        ;
    }

    public function testBip()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->bip($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->bip($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments("\007")->once()
        ;
    }

    public function testSave()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->save($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->save($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->atLeastOnce()
        ;
    }

    public function testRestore()
    {
        $this
            ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->if($helper = new TestedClass())
            ->then
                ->object($helper->restore($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->withArguments('')->once()
            ->given($this->calling($output)->isDecorated = true)
            ->then
                ->object($helper->restore($output))->isIdenticalTo($helper)
                ->mock($output)
                    ->call('write')->atLeastOnce()
        ;
    }
}
