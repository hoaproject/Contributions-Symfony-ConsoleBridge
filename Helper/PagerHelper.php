<?php

namespace Hoathis\SymfonyConsoleBridge\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

class PagerHelper extends Helper
{
    const NAME = 'pager';

    public function getName()
    {
        return self::NAME;
    }

    public function more(OutputInterface $output, $code, $display = false)
    {
        return $this->paginate(__FUNCTION__, $output, $code, $display);
    }

    public function less(OutputInterface $output, $code, $display = false)
    {
        return $this->paginate(__FUNCTION__, $output, $code, $display);
    }

    protected function paginate($type, OutputInterface $output, $code, $display = false)
    {
        if (true ===  $output->isDecorated()) {
            ob_start('Hoa\Console\Chrome\Pager::' . $type);
        } else {
            ob_start();
        }

        $code();

        $content = ob_get_clean();

        if (false ===  $output->isDecorated() || true === $display) {
            $output->write($content);
        }

        return $this;
    }
}
