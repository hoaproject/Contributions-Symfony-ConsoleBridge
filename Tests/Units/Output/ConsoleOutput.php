<?php

namespace Hoa\Console {
    use mock;

    class Console extends mock\staticClass {}
}

namespace Hoathis\SymfonyConsoleBridge\Tests\Units\Output {
    use atoum;
    use Hoa\Console\Console;
    use Hoathis\SymfonyConsoleBridge\Output\ConsoleOutput as TestedClass;
    use Symfony\Component\Console\Output\OutputInterface;

    class ConsoleOutput extends atoum
    {
        public function testClass()
        {
            $this
                ->testedClass
                    ->isSubclassOf('Symfony\Component\Console\Output\ConsoleOutputInterface')
            ;
        }

        public function test__construct()
        {
            $this
                ->given($console = Console::getInstance())
                ->and($this->calling($console)->isDirect = true)
                ->if($output = new TestedClass())
                ->then
                    ->boolean($output->isDecorated())
                    ->integer($output->getVerbosity())
            ;
        }

        public function testIsDecorated()
        {
            $this
                ->given($console = Console::getInstance())
                ->and($this->calling($console)->isDirect = true)
                ->if($output = new TestedClass())
                ->then
                    ->boolean($output->isDecorated())->isTrue()
                ->given($this->calling($console)->isDirect = false)
                ->if($output = new TestedClass())
                ->then
                    ->boolean($output->isDecorated())->isFalse()
            ;
        }

        public function testSetDecorated()
        {
            $this
                ->given($console = Console::getInstance())
                ->and($this->calling($console)->isDirect = false)
                ->if($output = new TestedClass())
                ->then
                    ->object($output->setDecorated(true))->isIdenticalTo($output)
                    ->boolean($output->isDecorated())->isTrue()
                    ->object($output->setDecorated(false))->isIdenticalTo($output)
                    ->boolean($output->isDecorated())->isFalse()
                ->given($this->calling($console)->isDirect = true)
                ->if($output = new TestedClass())
                ->then
                    ->object($output->setDecorated(true))->isIdenticalTo($output)
                    ->boolean($output->isDecorated())->isTrue()
                    ->object($output->setDecorated(false))->isIdenticalTo($output)
                    ->boolean($output->isDecorated())->isFalse()
            ;
        }

        public function testGetVerbosity()
        {
            $this
                ->given($console = Console::getInstance())
                ->and($this->calling($console)->isDirect = false)
                ->and($this->calling($console)->isRedirection = false)
                ->if($output = new TestedClass())
                ->then
                    ->integer($output->getVerbosity())->isEqualTo(OutputInterface::VERBOSITY_NORMAL)
                ->given($this->calling($console)->isDirect = true)
                ->if($output = new TestedClass())
                ->then
                    ->integer($output->getVerbosity())->isEqualTo(OutputInterface::VERBOSITY_VERBOSE)
                ->given($this->calling($console)->isDirect = false)
                ->given($this->calling($console)->isRedirection = true)
                ->if($output = new TestedClass())
                ->then
                    ->integer($output->getVerbosity())->isEqualTo(OutputInterface::VERBOSITY_VERY_VERBOSE)
            ;
        }

        public function testSetFormatter()
        {
            $this
                ->given($console = Console::getInstance())
                ->and($this->calling($console)->isDirect = false)
                ->and($formatter = new \mock\Symfony\Component\Console\Formatter\OutputFormatter())
                ->if($output = new TestedClass())
                ->then
                    ->object($output->setFormatter($formatter))->isIdenticalTo($output)
                    ->mock($formatter)
                        ->call('setDecorated')->withArguments(false)->once()
                ->given($this->calling($console)->isDirect = true)
                ->if($output = new TestedClass())
                ->then
                    ->object($output->setFormatter($formatter))->isIdenticalTo($output)
                    ->mock($formatter)
                        ->call('setDecorated')->withArguments(true)->once()
            ;
        }
    }
}
