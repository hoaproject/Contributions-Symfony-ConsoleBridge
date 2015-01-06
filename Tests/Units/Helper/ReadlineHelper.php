<?php

namespace Hoathis\SymfonyConsoleBridge\Tests\Units\Helper;

use atoum;
use Hoathis\SymfonyConsoleBridge\Helper\ReadlineHelper as TestedClass;
use Symfony\Component\Console\Formatter\OutputFormatter;

class ReadlineHelper extends atoum
{
    public function beforeTestMethod($method)
    {
        if (false === defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'r'));
        }
    }

    public function testClass()
    {
        $this
            ->testedClass
                ->isSubclassOf('Symfony\Component\Console\Helper\Helper')
            ->string(TestedClass::NAME)->isEqualTo('readline')
        ;
    }

    public function testGetName()
    {
        $this
            ->if($helper = new TestedClass())
            ->then
                ->string($helper->getName())->isEqualTo(TestedClass::NAME)
        ;
    }

    public function testRead()
    {
        $this
            ->given($readline = new \mock\Hoa\Console\Readline\Readline())
            ->and($this->calling($readline)->readLine = $line = uniqid())
            ->and($formatter = new OutputFormatter())
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($this->calling($output)->getFormatter = $formatter)
            ->if($helper = new TestedClass($readline))
            ->then
                ->string($helper->read($output, $message = uniqid()))->isEqualTo($line)
                ->mock($readline)
                    ->call('readLine')->withArguments($message)->once()
            ->given($formatter = new \mock\Symfony\Component\Console\Formatter\OutputFormatter())
            ->and($formatter->setDecorated(false))
            ->and($this->calling($output)->getFormatter = $formatter)
            ->then
                ->string($helper->read($output, $message = uniqid()))->isEqualTo($line)
                ->mock($readline)
                    ->call('readLine')->withArguments($message)->once()
                ->mock($formatter)
                    ->call('format')->withArguments($message)->once()
            ->given($this->calling($output)->isDecorated = true)
            ->and($this->calling($formatter)->format = $formatted = uniqid())
            ->then
                ->string($helper->read($output, $message = uniqid()))->isEqualTo($line)
                ->mock($readline)
                    ->call('readLine')->withArguments($formatted)->once()
                ->mock($formatter)
                    ->call('format')->withArguments($message)->once()
        ;
    }

    public function testSelect()
    {
        $this
            ->given($readline = new \mock\Hoa\Console\Readline\Readline())
            ->and($this->calling($readline)->readLine = $line = uniqid())
            ->and($this->calling($readline)->readLine[1] = $otherLine = uniqid())
            ->and($formatter = new OutputFormatter())
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($this->calling($output)->getFormatter = $formatter)
            ->if($helper = new TestedClass($readline))
            ->and($choices = array(
                $choice = uniqid(),
                $otherChoice = uniqid(),
                $line,
            ))
            ->and($autocompleter = new \mock\Hoa\Console\Readline\Autocompleter\Word(array()))
            ->then
                ->string($helper->select($output, $message = uniqid(), $choices, null, false, false, $autocompleter))->isEqualTo($line)
                ->mock($autocompleter)->call('setWords')->withArguments($choices)->once()
                ->mock($readline)->call('setAutocompleter')->withArguments($autocompleter)->once()
                ->mock($output)->call('writeln')
                    ->withArguments(sprintf('<error> Invalid input: %s </error>', $otherLine))->once()
                    ->withArguments('  <comment>' . $choice . '</comment>' . PHP_EOL . '  <comment>' . $otherChoice . '</comment>' . PHP_EOL . '  <comment>' . $line . '</comment>' . PHP_EOL)->once()
                ->mock($readline)->call('readLine')->withArguments($message)->twice()
            ->given($this->calling($readline)->readLine = null)
            ->if($default = $choice)
            ->then
                ->string($helper->select($output, $message = uniqid(), $choices, $default, false, false, $autocompleter))->isEqualTo($default)
                ->mock($output)->call('writeln')
                    ->withArguments('* <comment>' . $choice . '</comment>' . PHP_EOL . '  <comment>' . $otherChoice . '</comment>' . PHP_EOL . '  <comment>' . $line . '</comment>' . PHP_EOL)->once()
                ->mock($readline)->call('readLine')->withArguments($message)->once()
            ->given($this->resetMock($readline))
            ->given($this->resetMock($autocompleter))
            ->and($this->calling($readline)->readLine = $lineKey = uniqid())
            ->and($this->calling($readline)->readLine[1] = $randomKey = uniqid())
            ->if($choices = array(
                $choiceKey = uniqid() => $choice = uniqid(),
                $otherChoiceKey = uniqid() => $otherChoice = uniqid(),
                $lineKey => $line,
            ))
            ->then
                ->string($helper->select($output, $message = uniqid(), $choices, null, true, false, $autocompleter))->isEqualTo($lineKey)
                ->mock($autocompleter)->call('setWords')->withArguments(array_keys($choices))->once()
                ->mock($readline)->call('setAutocompleter')->withArguments($autocompleter)->once()
                ->mock($output)->call('writeln')
                    ->withArguments(sprintf('<error> Invalid input: %s </error>', $randomKey))->once()
                    ->withArguments('  <comment>' . $choiceKey . '</comment>: ' . $choice . PHP_EOL . '  <comment>' . $otherChoiceKey . '</comment>: ' . $otherChoice . PHP_EOL . '  <comment>' . $lineKey . '</comment>: ' . $line . PHP_EOL)->once()
                    ->withArguments($message)
            ->given($this->calling($readline)->readLine = null)
            ->if($default = $choiceKey)
            ->then
                ->string($helper->select($output, $message = uniqid(), $choices, $default, true, false, $autocompleter))->isEqualTo($default)
                ->mock($output)->call('writeln')
                    ->withArguments('* <comment>' . $choiceKey . '</comment>: ' . $choice . PHP_EOL . '  <comment>' . $otherChoiceKey . '</comment>: ' . $otherChoice . PHP_EOL . '  <comment>' . $lineKey . '</comment>: ' . $line . PHP_EOL)->once()
                    ->withArguments($message)
        ;
    }

    public function testAutocomplete()
    {
        $this
            ->given($readline = new \mock\Hoa\Console\Readline\Readline())
            ->and($this->calling($readline)->readLine = $line = uniqid())

            ->and($formatter = new OutputFormatter())
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($this->calling($output)->getFormatter = $formatter)
            ->if($helper = new TestedClass($readline))
            ->and($choices = array(
                $choice = uniqid(),
                $otherChoice = uniqid(),
                $line,
            ))
            ->and($autocompleter = new \mock\Hoa\Console\Readline\Autocompleter\Word(array()))
            ->then
                ->string($helper->autocomplete($output, $message = uniqid(), $autocompleter))->isEqualTo($line)
                ->mock($readline)
                    ->call('setAutocompleter')->withArguments($autocompleter)->once()
                    ->call('readLine')->withArguments($message)->once()
            ->given($this->calling($readline)->readLine = null)
            ->if($default = $choice)
            ->then
                ->string($helper->autocomplete($output, $message = uniqid(), $autocompleter, null, $default))->isEqualTo($default)
                ->mock($readline)
                    ->call('readLine')->withArguments($message)->once()
            ->given($this->resetMock($readline))
            ->given($this->resetMock($autocompleter))
            ->and($this->calling($readline)->readLine = $lineKey = uniqid())
            ->if($choices = array(
                $choiceKey = uniqid() => $choice = uniqid(),
                $otherChoiceKey = uniqid() => $otherChoice = uniqid(),
                $lineKey => $line,
            ))
            ->then
                ->string($helper->autocomplete($output, $message = uniqid(), $autocompleter))->isEqualTo($lineKey)
                ->mock($readline)
                    ->call('setAutocompleter')->withArguments($autocompleter)->once()
                    ->call('readLine')->withArguments($message)->once()
            ->given($this->calling($readline)->readLine = null)
            ->if($default = $choiceKey)
            ->then
                ->string($helper->autocomplete($output, $message = uniqid(), $autocompleter, null, $default))->isEqualTo($default)
                ->mock($readline)
                    ->call('setAutocompleter')->withArguments($autocompleter)->twice()
                    ->call('readLine')->withArguments($message)->once()
        ;
    }

    public function testValidate()
    {
        $this
            ->given($readline = new \mock\Hoa\Console\Readline\Readline())
            ->and($formatter = new OutputFormatter())
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($this->calling($output)->getFormatter = $formatter)
            ->if($helper = new TestedClass($readline))
            ->then
                ->exception(function() use ($helper, $output) {
                    $helper->validate($output, uniqid(), uniqid());
                })
                    ->isInstanceOf('InvalidArgumentException')
                    ->hasMessage('Argument is not callable')
            ->given($this->function->validator = true)
            ->and($this->function->validator[1] = false)
            ->and($this->calling($readline)->readLine = $line = uniqid())
            ->and($this->calling($readline)->readLine[1] = $otherLine = uniqid())
            ->then
                ->string($helper->validate($output, $message = uniqid(), $this->function->validator->getFunction()))->isEqualTo($line)
                ->function('validator')
                    ->wasCalledWithArguments($otherLine)->once()
                    ->wasCalledWithArguments($line)->once()
                ->mock($output)
                    ->call('writeln')->withArguments(sprintf('<error> Invalid input: %s </error>', $otherLine))->once()
                ->mock($readline)
                    ->call('readLine')->withArguments($message)->twice()
        ;
    }
}
