<?php

namespace Hoa\Console {
    use mock;

    class Cursor extends mock\staticClass {}
}

namespace Hoathis\SymfonyConsoleBridge\Tests\Units\Formatter {
    use atoum;
    use Hoa\Console\Cursor;
    use Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatterStyle as TestedClass;

    class OutputFormatterStyle extends atoum
    {
        public function testClass()
        {
            $this
                ->testedClass
                    ->isSubclassOf('Symfony\Component\Console\Formatter\OutputFormatterStyleInterface')
            ;
        }

        public function test__construct()
        {
            $this
                ->if($style = new TestedClass())
                ->then
                    ->variable($style->getForeground())->isNull()
                    ->variable($style->getBackground())->isNull()
                    ->array($style->getOptions())->isEmpty()
                ->given($foreground = uniqid())
                ->and($background = uniqid())
                ->and($options = array(uniqid()))
                ->if($style = new TestedClass($foreground, $background, $options))
                ->then
                    ->string($style->getForeground())->isEqualTo($foreground)
                    ->string($style->getBackground())->isEqualTo($background)
                    ->array($style->getOptions())->isEqualTo($options)
            ;
        }

        public function testGetSetForeground()
        {
            $this
                ->given($foreground = uniqid())
                ->if($style = new TestedClass())
                ->then
                    ->object($style->setForeground($foreground))->isIdenticalTo($style)
                    ->string($style->getForeground())->isEqualTo($foreground)
            ;
        }

        public function testGetSetBackground()
        {
            $this
                ->given($background = uniqid())
                ->if($style = new TestedClass())
                ->then
                    ->object($style->setBackground($background))->isIdenticalTo($style)
                    ->string($style->getBackground())->isEqualTo($background)
            ;
        }

        public function testGetSetOptions()
        {
            $this
                ->given($options = array(uniqid()))
                ->if($style = new TestedClass())
                ->then
                    ->object($style->setOptions($options))->isIdenticalTo($style)
                    ->array($style->getOptions())->isEqualTo($options)
            ;
        }

        public function testUnsetOption()
        {
            $this
                ->given($options = array($option = uniqid()))
                ->if($style = new TestedClass(null, null, $options))
                ->then
                    ->array($style->getOptions())->isEqualTo($options)
                    ->object($style->unsetOption($option))->isIdenticalTo($style)
                    ->array($style->getOptions())->isEmpty()
            ;
        }

        public function testApply()
        {
            $this
                ->given($text = uniqid())
                ->and($cursor = Cursor::getInstance())
                ->and($this->calling($cursor)->colorize = null)
                ->and($this->function->ob_start = function() { ob_start(); })
                ->and($this->function->ob_get_clean = function() { return ob_get_clean(); })
                ->if($style = new TestedClass())
                ->then
                    ->string($style->apply($text))->isEqualTo($text)
                    ->mock($cursor)
                        ->call('colorize')->withArguments('')
                            ->after($this->function('ob_start')->wasCalled())
                            ->before($this->mock($cursor)->call('colorize')->withArguments('fg(default) bg(default) n'))
                        ->once()
                        ->function('ob_get_clean')->wasCalled()
                            ->after($this->mock($cursor)->call('colorize')->withArguments('fg(default) bg(default) n'))
                        ->once()
                ->given($foreground = uniqid())
                ->and($background = uniqid())
                ->and($options = array(uniqid()))
                ->if($style = new TestedClass($foreground, $background, $options))
                ->and($style->apply($text))
                ->then
                    ->mock($cursor)
                        ->call('colorize')->withArguments(sprintf('fg(%s) bg(%s) %s', $foreground, $background, implode(' ', $options)))
                            ->before($this->mock($cursor)->call('colorize')->withArguments('fg(default) bg(default) n'))
                        ->once()
            ;
        }
    }
}
