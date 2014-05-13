![Hoa](http://static.hoa-project.net/Image/Hoa_small.png)

Hoa is a **modular**, **extensible** and **structured** set of PHP libraries.
Moreover, Hoa aims at being a bridge between industrial and research worlds.

# Hoathis\SymfonyConsoleBridge [![Build Status](https://travis-ci.org/jubianchi/HoathisSymfonyConsoleBridge.png?branch=master)](https://travis-ci.org/jubianchi/HoathisSymfonyConsoleBridge)

* [Installation](#installation)
* [How to use](#how-to-use)
  * [Symfony](#symfony)
  * [Output](#output)
  * [Formatter](#formatter)
  * [Helpers](#helpers)
    * [Window](#window)
    * [Cursor](#cursor)
    * [Readline](#readline)
    * [Pager](#pager)

## Installation

Add these lines to your `require-dev` section:

```json
{
    "require": {
        "hoa/core": "*@dev",
        "hoa/console": "*@dev",
        "hoa/string": "*@dev",
        "hoa/stream": "*@dev",
        "hoathis/symfony-console-bridge": "dev-master"
    }
}
```

Then install dependencies:

```sh
$ composer update hoathis/symfony-console-bridge
```

## How to use

### Symfony

### Output

`Hoathis\SymfonyConsoleBridge\Output\ConsoleOutput` is an alternative to the native `ConsoleOutput` which is able to detect
output type and automatically configure verbosity and text decoration.

Given you have the following command:

```php
<?php

use Hoathis\SymfonyConsoleBridge\Example\Application;
use Hoathis\SymfonyConsoleBridge\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$app = new Application();

$app
    ->register('example:output')
    ->setCode(function(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>I\'m a decorated text only in the console</info>');

        if ($output->getVerbosity() === OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln('I\'ll be displayed with the <comment>normal</comment> verbosity level');
        }

        if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln('I\'ll be displayed with the <comment>verbose</comment> verbosity level');
        }

        if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERY_VERBOSE) {
            $output->writeln('I\'ll be displayed with the <comment>very verbose</comment> verbosity level');
        }

        if ($output->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
            $output->writeln('I\'ll be displayed with the <comment>debug</comment> verbosity level');
        }
    })
;

$app->run(new ArgvInput(), new ConsoleOutput());
```

Running:

```sh
$ php app.php
# I'm a decorated text only in the console
# I'll be displayed with the verbose verbosity level
```

As you will see in your terminal, output will be decorated and verbose by default. But if you run:

```sh
$ php app.php > output
$ cat -vet output
# I'm a decorated text only in the console$
# I'll be displayed with the very verbose verbosity level$
```

The verbosity level will automitaclly be switched to very verbose because the output has detected that you were
redirecting it to a file.

Here are the rules used to determine verbosity level and text decoration support:

|          | Verbosity    | Decoration |
| -------- | ------------ | ---------- |
| Pipe     | normal       | disabled   |
| Redirect | very verbose | disabled   |
| Terminal | verbose      | enabled    |

Those rules will only be used if you do not provide any verbosity level using command line arguments. If you want to
redirect outputs to a file using the debug verbosity level, simply run:

```sh
$ php app.php -vvv > output
$ cat -vet output
# I'm a decorated text only in the console$
# I'll be displayed with the debug verbosity level$
```

You can still force ansi output using the `--ansi` option:

```sh
$ php app.php -vvv --ansi | xargs -0 echo -n | cat -vet
# ^[[38;5;189mI'm a decorated text only in the console^[[39;49;0m$
# I'll be displayed with the ^[[38;5;96mdebug^[[39;49;0m verbosity level$
```

### Formatter

`Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatterStyle` will let you do everything you were able to do with the native `symfony/console` formatter with some more cool things:

* Supports `xterm-8color` color names
* Supports `xterm-256color` color codes
* **Automatically translates hex color codes**
* Supports text styling (normal, bold, underlined, blink and inverse)

To use those new `OutputFormatterStyle`, use the usual API :

```php
<?php

namespace Hoathis\SymfonyConsoleBridge\Example;

use Hoathis\SymfonyConsoleBridge\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);

        $formatter = $output->getFormatter();
        $formatter->setStyle('info', new OutputFormatterStyle('#e4cbf4'));
        $formatter->setStyle('comment', new OutputFormatterStyle('#795290'));
        $formatter->setStyle('question', new OutputFormatterStyle('#de8300'));
        $formatter->setStyle('error', new OutputFormatterStyle('white', '#ff3333', array(OutputFormatterStyle::STYLE_BOLD)));
    }

    //...
}

```

As you can see in the previous example, you can replace built-in styles by simply redifining them with the new formatter.

### Helpers

The real power of the library are its helpers: they let you manager every terminal components. You will first have to
manually load them:

```php
<?php

namespace Hoathis\SymfonyConsoleBridge\Example;

use Hoathis\SymfonyConsoleBridge\Helper;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    protected function getDefaultHelperSet()
    {
        $set = parent::getDefaultHelperSet();

        $set->set(new Helper\WindowHelper());
        $set->set(new Helper\CursorHelper());
        $set->set(new Helper\ReadlineHelper());
        $set->set(new Helper\PagerHelper());

        return $set;
    }

    //...
}

```

#### Window

The window helper will let you manipulate the current terminal window. It provides several utility methods, each one
being bound to an action:

```php
<?php

use Hoathis\SymfonyConsoleBridge\Example\Application;
use Hoathis\SymfonyConsoleBridge\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$app = new Application();

$app
    ->register('example:window')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        $window = $app->getHelperSet()->get('window');

        $output->writeln('<info>I\'m going to bring your window to the foreground and back to the foreground after one second</info>');
        sleep(1);
        $window->lower($output);
        sleep(1);
        $window->raise($output);

        $output->writeln('<info>I\'m going to minimize your window and restore it after one second</info>');
        sleep(1);
        $window->minimize($output);
        sleep(1);
        $window->restore($output);
    })
;
```

Many other utility method are available

* `setTitle`, `getTitle`, `getLabel` to manipulate terminal title
* `setSize`, `getSize`, `move`, `setPosition`, `getPosition` to manipulate window position
* `minimize`, `restore`, `lower`, `raise` to manipulate window placement
* `scroll`, `refresh`, `copy` to manipulate window content

#### Cursor

#### Readline

#### Pager
