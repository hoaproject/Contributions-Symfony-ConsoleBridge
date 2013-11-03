<?php

namespace Hoathis\SymfonyConsoleBridge\Helper;

use Hoa\Console\Window;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

class WindowHelper extends Helper
{
    const NAME = 'window';

    const SCROLL_UP = '↑';
    const SCROLL_DOWN = '↓';

    public function getName()
    {
        return self::NAME;
    }

    public function setTitle(OutputInterface $output, $title)
    {
        $this->buffer($output, function() use (& $title) { Window::setTitle($title); });

        return $this;
    }

    public function getTitle(OutputInterface $output)
    {
        $this->buffer($output, function() use (& $title) { $title = Window::getTitle(); });

        return $title;
    }

    public function getLabel(OutputInterface $output)
    {
        $this->buffer($output, function() use (& $label) { $label = Window::getLabel(); });

        return $label;
    }

    public function resize($callback) {
        if (false === is_callable($callback)) {
            throw new \InvalidArgumentException('Argument is not callable');
        }

        declare(ticks = 1);

        event('hoa://Event/Console/Window:resize')->attach($callback);

        return $this;
    }

    public function setSize(OutputInterface $output, $width, $height)
    {
        $this->buffer($output, function() use ($width, $height) { Window::setSize($width, $height); });

        return $this;
    }

    public function getSize(OutputInterface $output)
    {
        $this->buffer($output, function() use (& $size) { $size = Window::getSize(); });

        return $size;
    }


    public function move(OutputInterface $output, $x, $y)
    {
        $this->buffer($output, function() use ($x, $y) { Window::moveTo($x, $y); });

        return $this;
    }

    public function setPosition(OutputInterface $output, $x, $y)
    {
        $this->buffer($output, function() use ($x, $y) { Window::setPosition($x, $y); });

        return $this;
    }

    public function getPosition(OutputInterface $output)
    {
        $this->buffer($output, function() use (& $position) { $position = Window::getPosition(); });

        return $position;
    }

    public function minimize(OutputInterface $output)
    {
        $this->buffer($output, function() { Window::minimize(); });
    }

    public function restore(OutputInterface $output)
    {
        $this->buffer($output, function() { Window::restore(); });
    }

    public function lower(OutputInterface $output)
    {
        $this->buffer($output, function() { Window::lower(); });
    }

    public function raise(OutputInterface $output)
    {
        $this->buffer($output, function() { Window::raise(); });
    }

    public function scroll(OutputInterface $output, $directions, $repeat = 1)
    {
        if (is_array($directions)) {
            $directions = implode(' ', $directions);
        }

        $this->buffer($output, function() use ($directions, $repeat) { Window::scroll($directions, $repeat); });

        return $this;
    }

    public function refresh(OutputInterface $output)
    {
        $this->buffer($output, function() { Window::refresh(); });

        return $this;
    }

    public function copy(OutputInterface $output, $data)
    {
        $this->buffer($output, function() use ($data) { Window::copy($data); });

        return $this;
    }

    protected function buffer(OutputInterface $output, $code)
    {
        ob_start();

        if ($output->isDecorated()) {
            $code();
        }

        $output->write(ob_get_clean());
    }
}
