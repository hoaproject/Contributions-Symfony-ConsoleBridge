<?php

namespace Hoathis\SymfonyConsoleBridge\Helper;

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
        $message .= ($default ? sprintf(' (<comment>%s</comment>)', $default) : '');
        $message = $output->getFormatter()->format($message);

        return $this->readline->readLine($message) ?: $default;
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

        $this->readline->setAutocompleter($autocompleter);

        $first = true;
        foreach ($choices as $key => $value) {
            if(self::SEPARATOR === $value) {
                $message .= PHP_EOL;

                if(is_string($key)) {
                    $message .= ($first ? '' : PHP_EOL) . $key;
                }
            } else {
                if($keyAsValues) {
                    $message .= PHP_EOL . sprintf(
                        '%s%s: %s',
                        null !== $default && $key === $default ? '* ' : '  ',
                        sprintf($keyAsValues ? '<comment>%s</comment>' : '%s', $key),
                        sprintf($keyAsValues ? '%s' : '<comment>%s</comment>', $value)
                    );
                } else {
                    $message .= PHP_EOL . sprintf(
                        '%s%s',
                        null !== $default && $value === $default ? '* ' : '  ',
                        sprintf($keyAsValues ? '%s' : '<comment>%s</comment>', $value)
                    );
                }
            }

            $first = false;
        }

        $output->writeln($message);

        $input = $this->validate(
            $output,
            ': ',
            function($data) use ($values, $multi) {
                if (true === $multi) {
                    $data = explode(' ', $data);
                } else {
                    $data = array($data);
                }

                return array_intersect($data, $values) === $data;
            },
            $default
        );

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
