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
        if (DIRECTORY_SEPARATOR == '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI');
        }

        return Console::isDirect($this->getStream());
    }

    public function setDecorated($decorated)
    {
        parent::setDecorated($this->decorated = $decorated);

        return $this;
    }

    public function isDecorated()
    {
        if (null === $this->decorated) {
            $this->decorated = $this->hasColorSupport();

            $this->setDecorated($this->decorated);
        }

        return $this->decorated;
    }

    public function setVerbosity($level)
    {
        parent::setVerbosity($this->verbosity = $level);

        return $this;
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

            $this->setVerbosity($level);
        }

        return $this->verbosity;
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $formatter->setDecorated($this->isDecorated());

        parent::setFormatter($formatter);

        return $this;
    }
} 