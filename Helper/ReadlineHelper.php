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

    public function __construct()
    {
        $this->readline = new Readline();
    }

    public function read(OutputInterface $output, $message, $default = null)
    {
        $message .= ($default ? sprintf(' (<comment>%s</comment>)', $default) : '');

        if ($output->isDecorated()) {
            $formatter = $output->getFormatter();
            $message = $formatter->format($message);
        }

        return $this->readline->readLine($message) ?: $default;
    }

    public function select(OutputInterface $output, $message, array $choices, $default = null, $keyAsValues = false, $multi = false)
    {
        $words = array();
        $values = $keyAsValues ? ($keys = array_keys($choices)) : array_values($choices);
        foreach($choices as $key => $value) {
            if($value !== self::SEPARATOR) {
                $words[] = $keyAsValues ? $key : $value;
            }
        }

        $this->readline->setAutocompleter(new Word($words));

        $first = true;
        foreach ($choices as $key => $value) {
            if(self::SEPARATOR === $value) {
                $message .= PHP_EOL;

                if(is_string($key)) {
                    $message .= ($first ? '' : PHP_EOL) . $key;
                }
            } else {
                $message .= PHP_EOL . sprintf(
                    '%s%s: %s',
                    null !== $default && $key === $default ? '* ' : '  ',
                    sprintf($keyAsValues ? '<comment>%s</comment>' : '%s', $key),
                    sprintf($keyAsValues ? '%s' : '<comment>%s</comment>', $value)
                );
            }

            $first = false;
        }

        $output->writeln($message);

        return $this->validate(
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
