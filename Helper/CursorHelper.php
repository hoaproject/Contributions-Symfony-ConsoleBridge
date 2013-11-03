<?php

namespace Hoathis\SymfonyConsoleBridge\Helper;

use Hoa\Console\Cursor;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

class CursorHelper extends Helper
{
    const NAME = 'cursor';

    const MOVE_UP = '↑';
    const MOVE_DOWN = '↓';
    const MOVE_LEFT = '←';
    const MOVE_RIGHT = '→';

    const CLEAR_SCREEN = '↕';
    const CLEAR_ABOVE = '↑';
    const CLEAR_BELOW = '↓';
    const CLEAR_LEFT = '←';
    const CLEAR_RIGHT = '→';

    const STYLE_BLOCK = '▋';
    const STYLE_UNDERLINE = '_';
    const STYLE_VERTICAL = '|';

    public function getName()
    {
        return self::NAME;
    }

    public function move(OutputInterface $output, $steps, $repeat = 1)
    {
        if (is_array($steps)) {
            $steps = implode(' ', $steps);
        }

        $this->buffer($output, function() use ($steps, $repeat) { Cursor::move($steps, $repeat); });

        return $this;
    }

    public function moveTo(OutputInterface $output, $x, $y)
    {
        $this->buffer($output, function() use ($x, $y) { Cursor::moveTo($x, $y); });

        return $this;
    }

    public function clear(OutputInterface $output, $parts)
    {
        if (is_array($parts)) {
            $parts = implode(' ', $parts);
        }

        $this->buffer($output, function() use ($parts) { Cursor::clear($parts); });

        return $this;
    }

    public function hide(OutputInterface $output)
    {
        $this->buffer($output, function() { Cursor::hide(); });

        return $this;
    }

    public function show(OutputInterface $output)
    {
        $this->buffer($output, function() { Cursor::show(); });

        return $this;
    }

    public function colorize(OutputInterface $output, $attributes)
    {
        if (is_array($attributes)) {
            $attributes = implode(' ', $attributes);
        }

        $this->buffer($output, function() use ($attributes) { Cursor::colorize($attributes); });

        return $this;
    }

    public function reset(OutputInterface $output)
    {
        return $this->colorize($output, 'n fg(default) bg(default)');
    }

    public function style(OutputInterface $output, $style, $blink = true)
    {
        $this->buffer($output, function() use ($style, $blink) { Cursor::setStyle($style, $blink); });

        return $this;
    }

    public function bip(OutputInterface $output)
    {
        $this->buffer($output, function() { Cursor::bip(); });

        return $this;
    }

    public function getPosition(OutputInterface $output)
    {
        $position = array('x' => null, 'y' => null);
        $this->buffer($output, function() use (& $position) { $position = Cursor::getPosition(); });

        return $position;
    }

    public function save(OutputInterface $output)
    {
        $this->buffer($output, function() { Cursor::save(); });

        return $this;
    }

    public function restore(OutputInterface $output)
    {
        $this->buffer($output, function() { Cursor::restore(); });

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
