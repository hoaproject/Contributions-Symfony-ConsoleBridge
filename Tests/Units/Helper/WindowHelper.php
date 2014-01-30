<?php

namespace Hoa\Console {
    use mock;

    class Window extends mock\staticClass {}
}

namespace Hoathis\SymfonyConsoleBridge\Tests\Units\Helper {
    use atoum;
    use Hoa\Console\Window;
    use Hoathis\SymfonyConsoleBridge\Helper\WindowHelper as TestedClass;

    class WindowHelper extends atoum
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
                ->string(TestedClass::NAME)->isEqualTo('window')
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

        public function testSetTitle()
        {
            $this
                ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
                ->and($window = Window::getInstance())
                ->and($this->calling($window)->setTitle = null)
                ->if($helper = new TestedClass())
                ->then
                    ->object($helper->setTitle($output, $title = uniqid()))->isIdenticalTo($helper)
                    ->mock($window)->call('setTitle')->never()
                ->given($this->calling($output)->isDecorated = true)
                ->then
                    ->object($helper->setTitle($output, $title))->isIdenticalTo($helper)
                    ->mock($window)->call('setTitle')->withArguments($title)->once()
            ;
        }

        public function testGetTitle()
        {
            $this
                ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
                ->and($window = Window::getInstance())
                ->and($this->calling($window)->getTitle = $title = uniqid())
                ->if($helper = new TestedClass())
                ->then
                    ->variable($helper->getTitle($output))->isNull()
                    ->mock($window)->call('getTitle')->never()
                ->given($this->calling($output)->isDecorated = true)
                ->then
                    ->string($helper->getTitle($output))->isEqualTo($title)
                    ->mock($window)->call('getTitle')->once()
            ;
        }

        public function testGetLabel()
        {
            $this
                ->given($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
                ->and($window = Window::getInstance())
                ->and($this->calling($window)->getLabel = $label = uniqid())
                ->if($helper = new TestedClass())
                ->then
                    ->variable($helper->getLabel($output))->isNull()
                    ->mock($window)->call('getLabel')->never()
                ->given($this->calling($output)->isDecorated = true)
                ->then
                    ->string($helper->getLabel($output))->isEqualTo($label)
                    ->mock($window)->call('getLabel')->once()
            ;
        }

        public function testResize()
        {
            $this
                ->given($this->function->event = $event = new \mock\Hoa\Core\Event\Event())
                ->if($helper = new TestedClass())
                ->then
                    ->exception(function() use ($helper) {
                        $helper->resize(uniqid());
                    })
                        ->isInstanceOf('InvalidArgumentException')
                        ->hasMessage('Argument is not callable')
                    ->object($helper->resize($callback = function() {}))->isIdenticalTo($helper)
                    ->function('event')->wasCalledWithArguments('hoa://Event/Console/Window:resize')->once()
                    ->mock($event)->call('attach')->withArguments($callback)->once()
            ;
        }
    }
}
