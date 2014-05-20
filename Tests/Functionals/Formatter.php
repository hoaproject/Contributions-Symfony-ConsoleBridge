<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals;

use Hoa\Console;

class Formatter {

    protected $_colors    = array(
        T_ABSTRACT                 => 'foreground(#5fafd7) background(#e4cbf4)',
        T_AS                       => 'foreground(#5fafd7) background(#e4cbf4)',
        T_BREAK                    => 'foreground(#5fafd7) background(#e4cbf4)',
        T_CASE                     => 'foreground(#5fafd7) background(#e4cbf4)',
        T_CATCH                    => 'foreground(#5fafd7) background(#e4cbf4)',
        T_CLASS                    => 'foreground(#5fafd7) background(#e4cbf4)',
        T_CLONE                    => 'foreground(#5fafd7) background(#e4cbf4)',
        T_COMMENT                  => 'foreground(#839496) background(#e4cbf4)',
        T_CONSTANT_ENCAPSED_STRING => 'foreground(#859900) background(#e4cbf4)',
        T_CONTINUE                 => 'foreground(#5fafd7) background(#e4cbf4)',
        T_DECLARE                  => 'foreground(#5fafd7) background(#e4cbf4)',
        T_DEFAULT                  => 'foreground(#5fafd7) background(#e4cbf4)',
        T_DIR                      => 'foreground(#d33682) background(#e4cbf4)',
        T_DNUMBER                  => 'foreground(#d33682) background(#e4cbf4)',
        T_DO                       => 'foreground(#5fafd7) background(#e4cbf4)',
        T_DOC_COMMENT              => 'foreground(#839496) background(#e4cbf4)',
        T_ELSE                     => 'foreground(#5fafd7) background(#e4cbf4)',
        T_ELSEIF                   => 'foreground(#5fafd7) background(#e4cbf4)',
        T_EXIT                     => 'foreground(#5fafd7) background(#e4cbf4)',
        T_EXTENDS                  => 'foreground(#5fafd7) background(#e4cbf4)',
        T_FILE                     => 'foreground(#5fafd7) background(#e4cbf4)',
        T_FILE                     => 'foreground(#d33682) background(#e4cbf4)',
        T_FINALLY                  => 'foreground(#5fafd7) background(#e4cbf4)',
        T_FOR                      => 'foreground(#5fafd7) background(#e4cbf4)',
        T_FOREACH                  => 'foreground(#5fafd7) background(#e4cbf4)',
        T_FUNCTION                 => 'foreground(#5fafd7) background(#e4cbf4)',
        T_GLOBAL                   => 'foreground(#5fafd7) background(#e4cbf4)',
        T_GOTO                     => 'foreground(#5fafd7) background(#e4cbf4)',
        T_IF                       => 'foreground(#5fafd7) background(#e4cbf4)',
        T_IMPLEMENTS               => 'foreground(#5fafd7) background(#e4cbf4)',
        T_INCLUDE                  => 'foreground(#5fafd7) background(#e4cbf4)',
        T_INCLUDE_ONCE             => 'foreground(#5fafd7) background(#e4cbf4)',
        T_LNUMBER                  => 'foreground(#d33682) background(#e4cbf4)',
        T_LNUMBER                  => 'foreground(#d33682) background(#e4cbf4)',
        T_NAMESPACE                => 'foreground(#5fafd7) background(#e4cbf4)',
        T_NEW                      => 'foreground(#5fafd7) background(#e4cbf4)',
        T_OPEN_TAG                 => 'foreground(#cb4b16) background(#e4cbf4)',
        T_OPEN_TAG_WITH_ECHO       => 'foreground(#cb4b16) background(#e4cbf4)',
        T_PRIVATE                  => 'foreground(#5fafd7) background(#e4cbf4)',
        T_PROTECTED                => 'foreground(#5fafd7) background(#e4cbf4)',
        T_PUBLIC                   => 'foreground(#5fafd7) background(#e4cbf4)',
        T_REQUIRE                  => 'foreground(#5fafd7) background(#e4cbf4)',
        T_REQUIRE_ONCE             => 'foreground(#5fafd7) background(#e4cbf4)',
        T_RETURN                   => 'foreground(#5fafd7) background(#e4cbf4)',
        T_STATIC                   => 'foreground(#5fafd7) background(#e4cbf4)',
        T_STRING                   => 'foreground(#afaf87) background(#e4cbf4)',
        T_SWITCH                   => 'foreground(#5fafd7) background(#e4cbf4)',
        T_THROW                    => 'foreground(#5fafd7) background(#e4cbf4)',
        T_TRAIT                    => 'foreground(#5fafd7) background(#e4cbf4)',
        T_TRY                      => 'foreground(#5fafd7) background(#e4cbf4)',
        T_USE                      => 'foreground(#5fafd7) background(#e4cbf4)',
        T_VARIABLE                 => 'foreground(#d7b05f) background(#e4cbf4)',
        T_WHILE                    => 'foreground(#5fafd7) background(#e4cbf4)',
        T_YIELD                    => 'foreground(#5fafd7) background(#e4cbf4)',
        'highlight'                => 'foreground(#795290) background(#e4cbf4)',
        '*'                        => 'foreground(#fdf6e3) background(#e4cbf4)'
    );
    protected $_tokens    = array();
    protected $_higlights = array();

    /**
     * Foobar.
     */
    public function __construct ( $code, Array $colors = array() ) {

        $this->_tokens = token_get_all($code);

        foreach($colors as $token => $color)
            $this->_colors[$token] = $color;

        return;
    }

    public function highlight ( $lines ) {

        if(!is_array($lines))
            $lines = array($lines);

        foreach($lines as $line)
            $this->_highlights[$line] = true;

        return;
    }

    /**
     * Compute.
     */
    protected function compute ( ) {

        $out = Console\Chrome\Text::colorize(' ', 'background(#e4cbf4)');
        $max = 0;
        $currLine = 1;

        foreach($this->_tokens as $token) {
            $style = null;

            if(isset($this->_highlights[$currLine])) {
                $style = 'foreground(#795290) background(#FFFFFF)';
            }

            if(!is_array($token)) {

                $out .= Console\Chrome\Text::colorize(
                    $token,
                    $style ?: $this->_colors['*']
                );

                continue;
            }

            list($tokenType, $tokenValue, ) = $token;

            $lines = explode("\n", $tokenValue);
            $count = count($lines);

            $length = 0;
            foreach($lines as $line) {
                if(isset($this->_colors[$tokenType]))
                    $out .= Console\Chrome\Text::colorize(
                        $line,
                        $style ?: $this->_colors[$tokenType]
                    );
                else
                    $out .= Console\Chrome\Text::colorize(
                        $line,
                        $style ?: $this->_colors['*']
                    );

                $length += strlen($line);
                $max = $length > $max ? $length : $max;

                if(0 < --$count) {
                    $out .= "\n" . Console\Chrome\Text::colorize(' ', 'background(#e4cbf4)');
                    $currLine++;
                    $length = 0;
                }
            }
        }

        $oout = null;
        $max += 1;
        $lines = explode("\n", $out);

        foreach($lines as $number => $line) {
            $style = null;

            if(isset($this->_highlights[$currLine])) {
                $style = 'background(#FFFFFF)';
            }

            $trimmed = rtrim($line);
            $padding = $max - strlen(preg_replace('/\\033\[(?:\d+;?)+m/', '', $trimmed));

            ++$number;
            $oout .= Console\Chrome\Text::colorize(
                    sprintf('%4d ', $number),
                    isset($this->_highlights[$number])
                        ? $this->_colors['highlight']
                        : 'foreground(#e4cbf4) background(#795290)'
                ) .
                $trimmed .
                Console\Chrome\Text::colorize(
                    str_repeat(' ', $padding) . ($trimmed !== $line ? PHP_EOL : ''),
                    $style ?: 'background(#e4cbf4)'
                ) .
                PHP_EOL;
        }

        return $oout;
    }

    public function __toString ( ) {

        return $this->compute();
    }
}
