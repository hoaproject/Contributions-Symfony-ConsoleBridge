<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Functionals;

use Hoa\Console;

require_once __DIR__ . '/../../vendor/autoload.php';

const T_PUNCTUATION = 390;

class Highlighter
{
    const TOKEN_TYPE = 0;
    const TOKEN_VALUE = 1;
    const TOKEN_LINE = 2;

    protected $colors = array(
        T_ABSTRACT                 => 'foreground(#5fafd7)',
        T_AS                       => 'foreground(#5fafd7)',
        T_BREAK                    => 'foreground(#5fafd7)',
        T_CASE                     => 'foreground(#5fafd7)',
        T_CATCH                    => 'foreground(#5fafd7)',
        T_CLASS                    => 'foreground(#5fafd7)',
        T_CLONE                    => 'foreground(#5fafd7)',
        T_COMMENT                  => 'foreground(#839496)',
        T_CONSTANT_ENCAPSED_STRING => 'foreground(#859900)',
        T_CONTINUE                 => 'foreground(#5fafd7)',
        T_DECLARE                  => 'foreground(#5fafd7)',
        T_DEFAULT                  => 'foreground(#5fafd7)',
        T_DIR                      => 'foreground(#d33682)',
        T_DNUMBER                  => 'foreground(#d33682)',
        T_DO                       => 'foreground(#5fafd7)',
        T_DOC_COMMENT              => 'foreground(#839496)',
        T_ELSE                     => 'foreground(#5fafd7)',
        T_ELSEIF                   => 'foreground(#5fafd7)',
        T_EXIT                     => 'foreground(#5fafd7)',
        T_EXTENDS                  => 'foreground(#5fafd7)',
        T_FILE                     => 'foreground(#d33682)',
        T_FINALLY                  => 'foreground(#5fafd7)',
        T_FOR                      => 'foreground(#5fafd7)',
        T_FOREACH                  => 'foreground(#5fafd7)',
        T_FUNCTION                 => 'foreground(#5fafd7)',
        T_GLOBAL                   => 'foreground(#5fafd7)',
        T_GOTO                     => 'foreground(#5fafd7)',
        T_IF                       => 'foreground(#5fafd7)',
        T_IMPLEMENTS               => 'foreground(#5fafd7)',
        T_INCLUDE                  => 'foreground(#5fafd7)',
        T_INCLUDE_ONCE             => 'foreground(#5fafd7)',
        T_LNUMBER                  => 'foreground(#d33682)',
        T_NAMESPACE                => 'foreground(#5fafd7)',
        T_NEW                      => 'foreground(#5fafd7)',
        T_OPEN_TAG                 => 'foreground(#cb4b16)',
        T_OPEN_TAG_WITH_ECHO       => 'foreground(#cb4b16)',
        T_PRIVATE                  => 'foreground(#5fafd7)',
        T_PROTECTED                => 'foreground(#5fafd7)',
        T_PUBLIC                   => 'foreground(#5fafd7)',
        T_REQUIRE                  => 'foreground(#5fafd7)',
        T_REQUIRE_ONCE             => 'foreground(#5fafd7)',
        T_RETURN                   => 'foreground(#5fafd7)',
        T_STATIC                   => 'foreground(#5fafd7)',
        T_STRING                   => 'foreground(#afaf87)',
        T_SWITCH                   => 'foreground(#5fafd7)',
        T_THROW                    => 'foreground(#5fafd7)',
        T_TRAIT                    => 'foreground(#5fafd7)',
        T_TRY                      => 'foreground(#5fafd7)',
        T_USE                      => 'foreground(#5fafd7)',
        T_VARIABLE                 => 'foreground(#d7b05f)',
        T_WHILE                    => 'foreground(#5fafd7)',
        T_YIELD                    => 'foreground(#5fafd7)',
        '*'                        => 'foreground(#fdf6e3)',
        'gutter' => array(
            true                   => 'foreground(#eee8d5) background(#073642) bold',
            false                  => 'foreground(#586e75) background(#002b36) bold'
        ),
        'line' => array(
            true                   => ' background(#374549) bold',
            false                  => ' background(#073642)'
        )
    );

    protected $tokens = [];
    protected $highlights = [];
    protected $maxlength = 0;
    protected $length = [];

    public function __construct($content)
    {
        $this->normalize(token_get_all($content));
    }

    public function normalize(array $tokens)
    {
        $normalized = array();
        $linenum = 1;
        $length = 0;

        foreach ($tokens as $token) {
            if (false === is_array($token)) {
                $token = [T_PUNCTUATION, $token, $linenum];
            }

            if (false === isset($normalized[$linenum])) {
                $normalized[$linenum] = [];
            }

            if (false === strpos($token[self::TOKEN_VALUE], PHP_EOL)) {
                $normalized[$linenum][] = $token;
                $length += strlen($token[self::TOKEN_VALUE]);
            } else {
                $lines = explode("\n", $token[self::TOKEN_VALUE]);

                foreach ($lines as $num => $line) {
                    if (false === isset($normalized[$linenum])) {
                        $normalized[$linenum] = [];
                    }

                    if('' !== $line) {
                        $normalized[$linenum][] = [
                            $token[self::TOKEN_TYPE],
                            $line,
                            $linenum,
                            'self'
                        ];
                        $length += strlen($line);
                    }

                    if ($num < count($lines) - 1) {
                        if($length > $this->maxlength) {
                            $this->maxlength = $length;
                        }

                        $this->length[$linenum] = $length;
                        $length = 0;
                        $linenum++;
                    }
                }
            }
        }

        $this->tokens = $normalized;

        return $this;
    }

    public function highlight(array $highlights)
    {
        $this->highlights = $highlights;

        return $this;
    }

    public function format()
    {
        $formatted = '';

        foreach ($this->tokens as $number => $line) {
            $highlighted = in_array($number, $this->highlights);
            $padding = $this->maxlength - $this->length[$number];

            $formatted .= $this->gutter($number, $highlighted);
            $formatted .= $this->line($line, $padding, $highlighted);
        }

        return $formatted;
    }

    public function gutter($linenum, $highlighted = false)
    {
        return Console\Chrome\Text::colorize(sprintf(' %3d ', $linenum), $this->colors['gutter'][$highlighted]);
    }

    public function line($code, $padding, $highlighted = false)
    {
        $content = '';
        foreach($code as $token) {
            $content .= Console\Chrome\Text::colorize(
                $token[self::TOKEN_VALUE],
                isset($this->colors[$token[self::TOKEN_TYPE]])
                    ? $this->colors[$token[self::TOKEN_TYPE]] . $this->colors['line'][$highlighted]
                    : $this->colors['*'] . $this->colors['line'][$highlighted]
            );
        }

        return Console\Chrome\Text::colorize(' ', $this->colors['line'][$highlighted])
            . rtrim($content, PHP_EOL)
            . Console\Chrome\Text::colorize(str_repeat(' ', $padding + 1), $this->colors['line'][$highlighted])
            . PHP_EOL;
    }
}
