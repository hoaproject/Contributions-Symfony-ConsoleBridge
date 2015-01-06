<?php

namespace Hoathis\SymfonyConsoleBridge\Helper;

use Hoa\Console\Readline\Autocompleter\Autocompleter;
use Hoa\Console\Readline\Autocompleter\Word;
use Hoa\Console\Readline\Readline;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

class ReadlineHelper extends Helper
{
    const NAME = 'readline';

    const SEPARATOR = '__SEPARATOR__';

    public function getName()
    {
        return self::NAME;
    }

    public function __construct(Readline $readline = null)
    {
        $this->readline = $readline ?: new Readline();
    }

    public function read(OutputInterface $output, $message, $default = null)
    {
        $message = $output->getFormatter()->format($message);

        return $this->readline->readLine($message) ?: $default;
    }

    public function autocomplete(OutputInterface $output, $message, Autocompleter $autocompleter, $validator = null, $default = null)
    {
        $this->readline->setAutocompleter($autocompleter);

        return $this->validate($output, $message, $validator ?: function() { return true; }, $default);
    }

    public function select(OutputInterface $output, $message, array $choices, $default = null, $keyAsValues = false, $multi = false, Word $autocompleter = null)
    {
        $words = array();
        $values = $keyAsValues ? array_keys($choices) : array_values($choices);
        foreach($choices as $key => $value) {
            if($value !== self::SEPARATOR) {
                $words[] = $keyAsValues ? $key : $value;
            }
        }

        if (null === $autocompleter) {
            $autocompleter = new Word($words);
        } else {
            $autocompleter->setWords($words);
        }

        $list = '';
        foreach ($choices as $key => $value) {
            if(self::SEPARATOR === $value) {
                if(is_string($key)) {
                    $list .= $key;
                }
            } else {
                if($keyAsValues) {
                    $list .= sprintf(
                        '%s%s: %s',
                        null !== $default && $key === $default ? '* ' : '  ',
                        sprintf($keyAsValues ? '<comment>%s</comment>' : '%s', $key),
                        sprintf($keyAsValues ? '%s' : '<comment>%s</comment>', $value)
                    );
                } else {
                    $list .= sprintf(
                        '%s%s',
                        null !== $default && $value === $default ? '* ' : '  ',
                        sprintf($keyAsValues ? '%s' : '<comment>%s</comment>', $value)
                    );
                }
            }

            $list .= PHP_EOL;
        }

        $output->writeln($list);

        $validator = function($data) use ($values, $multi) {
            if (true === $multi) {
                $data = explode(' ', $data);
            } else {
                $data = array($data);
            }

            return array_intersect($data, $values) === $data;
        };

        $input = $this->autocomplete($output, $message, $autocompleter, $validator, $default);

        if (true === $multi) {
            $input = explode(' ', $input);
        }

        return $input;
    }

    public function validate(OutputInterface $output, $message, $validator, $default = null)
    {
        if (false === is_callable($validator)) {
            throw new \InvalidArgumentException('Argument is not callable');
        }

        $data = null;
        $error = null;
        do {
            if(null !== $error) {
                $output->writeln(sprintf($error ?: '<error> Invalid input: %s </error>', $data));
            }

            $data = $this->read($output, $message, $default);
        } while(true !== ($error = $validator($data)));

        return $data;
    }
}
