<?php

namespace Hoathis\SymfonyConsoleBridge\Formatter;

use Symfony\Component\Console\Formatter\OutputFormatter as BaseFormatter;

class OutputFormatter extends BaseFormatter
{
    public function addStyle($name, $foreground = null, $background = null, array $options = array())
    {
        $this->setStyle($name, new OutputFormatterStyle($foreground, $background, $options));

        return $this;
    }
}
