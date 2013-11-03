<?php

namespace Hoathis\SymfonyConsoleBridge\Output;

use Hoa\Console\Console;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput as BaseOutput;

class ConsoleOutput extends BaseOutput
{
    protected $decorated;
    protected $verbosity;

    protected function hasColorSupport()
    {
        return Console::isDirect($this->getStream()) && parent::hasColorSupport();
    }

    public function setDecorated($decorated)
    {
        parent::setDecorated($this->decorated = $decorated);
    }

    public function isDecorated()
    {
        if (null === $this->decorated) {
            $this->decorated = Console::isDirect($this->getStream());

            $this->setDecorated($this->decorated);
        }

        return $this->decorated;
    }

    public function getVerbosity()
    {
        if (null === $this->verbosity) {
            $stream = $this->getStream();

            switch (true) {
                case Console::isDirect($stream):
                    $level = OutputInterface::VERBOSITY_VERBOSE;
                    break;

                case Console::isRedirection($stream):
                    $level = OutputInterface::VERBOSITY_VERY_VERBOSE;
                    break;

                default:
                    $level = OutputInterface::VERBOSITY_NORMAL;
            }

            $this->verbosity = $level;
        }

        return $this->verbosity;
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $formatter->setDecorated($this->isDecorated());

        parent::setFormatter($formatter);
    }
} 