<?php

namespace Hoathis\SymfonyConsoleBridge\Helper;

use Hoa\Console\Tput;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

class TputHelper extends Tput implements HelperInterface
{
    const NAME = 'tput';

    protected $helperSet = null;

    public function getName()
    {
        return self::NAME;
    }

    public function get($name)
    {
        switch (true)
        {
            case in_array($name, self::$_booleans):
                return $this->has($name);

            case in_array($name, self::$_numbers):
                return $this->count($name);

            case in_array($name, self::$_strings):
                return parent::get($name);

            default:
                throw new \InvalidArgumentException(sprintf('Invalid identifier %s', $name));
        }
    }

    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;
    }

    public function getHelperSet()
    {
        return $this->helperSet;
    }
}
