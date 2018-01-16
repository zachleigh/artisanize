# Artisanize
#### Use Laravel Artisan command syntax in any Symfony Console command.
[![Latest Stable Version](https://poser.pugx.org/zachleigh/artisanize/v/stable)](https://packagist.org/packages/zachleigh/artisanize)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](//packagist.org/packages/zachleigh/artisanize)
[![Build Status](https://img.shields.io/travis/zachleigh/artisanize/master.svg)](https://travis-ci.org/zachleigh/artisanize)
[![Quality Score](https://img.shields.io/scrutinizer/g/zachleigh/artisanize.svg)](https://scrutinizer-ci.com/g/zachleigh/artisanize/)
[![StyleCI](https://styleci.io/repos/117339527/shield?style=flat)](https://styleci.io/repos/117339527)     

## Contents
  - [Installation](#installation)
  - [Writing Commands](#writing-commands)
    - [Command Signature](#command-signature)
      - [Defining Command Arguments](#defining-command-arguments)
      - [Defining Command Options](#defining-command-options)
    - [Accessing Command Arguments And Options](#accessing-command-arguments-and-options)
    - [Asking for Confirmation](#asking-for-confirmation)
    - [Asking a Question](#asking-a-question)
    - [Asking for a Password](#asking-for-a-password)
    - [Choosing from a List](#choosing-from-a-list)
    - [Autocompletion](#autocompletion)
    - [Multiple Choice](#multiple-choice)
    - [Command Output](#command-output)
  - [Credits and Contributing](#credits-and-contributing)

## Installation
Install into any new or existing project that uses Symfony Console via composer:
```
composer require zachleigh/artisanize
```

Then, in your command files, rather than extend Symfony's base command
(Symfony\Component\Console\Command\Command), extend the Artisanize Command class
(Artisanize\Command). Define `signature` and `description` properties and a `handle`
method on the class.
```php
use Artisanize\Command;

class MyCommand extends Command
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'signature';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Description.';

    /**
     * Handle the command.
     */
    protected function handle()
    {
        // Handle your command
    }
}
```

## Writing Commands
A command class has three components: a signature, a description, and a handle method.
```php
use Artisanize\Command;

class ExampleCommand extends Command
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'namespace:name {argument} {--o|option=default}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Command decription.';

    /**
     * Handle the command.
     */
    protected function handle()
    {
        // handle the command
    }
}
```

`signature` is where you define your command's name, arguments, and options. This
is discussed in detail below. `description` is where you can set a description
message for your command to be displayed when using the console. The `handle` method
will be called when the command is fired and is where you should write the logic
for your command.

### Command Signature
The command signature is written in the same way that the command will be used in
the console and consists of three parts: the command name, arguments, and options.
The command name must come first in the signature and can be namespaced by prefixing
the command name with a namespace followed by a colon (':'):
```php
protected $signature = 'namespace:name';
```

Arguments and options are enclosed in curly braces and follow the command name.
Options are prefixed by two dashes ('--').

#### Defining Command Arguments
A standard argument consists of the argument name wrapped in curly braces:
```php
protected $signature = 'namespace:name {arg} {--option}'
```

The argument name, `arg` in the example above, is used to access the argument
value via the [`argument` method](#accessing-command-arguments-and-options).   

To make an argument optional, append a question mark ('?') to the argument name:
```php
protected $signature = 'namespace:name {arg?} {--option}'
```
     
To give the argument a default value, separate the argument name and the default
value with an equals sign ('='):
```php
protected $signature = 'namespace:name {arg=default} {--option}'
```

If no value is provided for the argument, the default value will be used.   

If the argument is in array form, append an asterisk ('*') to the argument name:
```php
protected $signature = 'namespace:name {arg*} {--option}'
```

Arguments can then be passed to the command by space separating them:
```
php app.php namespace:name one two three
```

This will set the value of `arg` to `['one', 'two', 'three']`.   

Argument arrays can also be set as optional:
```php
protected $signature = 'namespace:name {arg?*} {--option}'
```

When accessing optional argument arrays, arguments that have not been passed
equal an empty array.
     
It is often helpful to provide a description with an argument. To do this, add a
colon (':') after the argument definition and append the description:
```php
protected $signature = 'namespace:name {arg=default : Argument description} {--option}'
```

#### Defining Command Options
A standard option consists of the option, prefixed by two dashes ('--'), wrapped
in curly braces:
```php
protected $signature = 'namespace:name {argument} {--opt}'
```
The option name, `opt`, is used to access the argument value via the
[`option` method](#accessing-command-arguments-and-options). Standard options do
not take values and act as true/false flags: the presence of the option when the
command is called sets its value to true and if it is not present, the value is false.  

To define an option with a required value, append an equals sign ('=') to the option name:
```php
protected $signature = 'namespace:name {argument} {--opt=}'
```
     
To set a default value, place it after the equals sign:
```php
protected $signature = 'namespace:name {argument} {--opt=default}'
```
    
Options may also have shortcuts to make them easier to remember and use. To set
a shortcut, prepend it to the command name and separate the two with a pipe ('|'):
```php
protected $signature = 'namespace:name {argument} {--o|opt}'
```

Now, the option may be called in the standard way:
```
php app.php namespace:name argument --opt
```
Or by using the shortcut:
```
php app.php namespace:name argument -o
```

Options may also be passed as arrays:
```php
protected $signature = 'namespace:name {argument} {--opt=*}'
```

When passing options arrays, each value must be prefixed by the option name:
```
php app.php namespace:name argument --opt=one --opt=two --opt=three
```
The value of `opt` will be set to `['one', 'two', 'three']`.   

Just like with arguments, the option description can best by appending a colon
(':') and the description to the option name definiton:
```php
protected $signature = 'namespace:name {argument} {--o|opt : option description.}'
```

### Accessing Command Arguments And Options
To access arguments in the handle method, use the command class `argument` method.
If an argument name is given, it will return the value of the argument and if nothing
is passed, it will return an array of all arguments:
```php
protected function handle()
{
    $arg = $this->argument('arg'); // passed value of arg

    $allArguments = $this->argument(); // array of all arguments
}
```
    
The `option` method works in the exact same way:
```php
protected function handle()
{
    $opt = $this->option('opt'); // passed value of opt

    $allOptions = $this->option(); // array of all options
}
```

There are also `hasArgument` and `hasOption` methods on the command object:
```php
protected function handle()
{
    $argExists = $this->hasArgument('exists');  // true

    $optExists = $this->hasOption('doesntExist');  // false
}
```

### Asking for Confirmation
The `confirm` method can be used to ask the user for simple confirmation:
```php
if ($this->confirm('Do you wish to continue? ')) {
    // user answered true
}
```

### Asking a Question
The `ask` method can be used to ask a user a question. Pass a default value as
the second argument:
```php
$name = $this->ask('What is your name?', 'Nobody');
```

### Asking for a Password
The user answer can be hidden by using the `askPassword` method:
```php
$password = $this->askPassword('Please type your password');
```

### Choosing From a List
The `choose` method only allows an answer from a predefined list of choices. The
default value can be passed as the third argument:
```php
$car = $this->choose('What is your favourite car?', ['Ferrari', 'Lamborghini', 'Maserati'], 1);
```

### Autocompletion
The `anticipate` method can provide the user with some auto-completion help when
starting to write. The user can still provide any answer, regardless of the
auto-completion hints:
```php
$food = $this->anticipate('What is your favourite food?', ['Pizza', 'Pasta', 'Lasagna'], 'Mozzarella');
```

### Multiple Choice
When the user should be allowed to choose more than a single answer, the `choice`
method allows them to select multiple items from a list. The third argument contains
a string of comma-separated defaults:
```php
$colors = $this->choice('What are your favourite colors (defaults to blue and red)?', ['Blue', 'Red', 'Green'], '0,1');
```

### Command Output
Every command has an `output` variable stored on the object that has several
methods to help write output to the console.   

The `write` method outputs plain unformatted text, `writeInfo` outputs green text,
`writeError` outputs red text, and `writeComment` outputs yellow text:
```php
protected function handle()
{
    $this->output->write('Message'); // plain text

    $this->output->writeInfo('Message');  // green text

    $this->output->writeError('Message');  // red text

    $this->output->writeComment('Message');  // yellow text
}
```
    
The output variable is a simple wrapper around Symfony's output class. To access
this class, use the `getOutputInterface` method:
```php
protected function handle()
{
    $output = $this->getOutputInterface(); // $output is instance of Symfony\Component\Console\Output\OutputInterface
}
```

Keep in mind that since the Artisanize Command class simply wraps up the Symfony
console component, all Symfony command features are still available on your command
objects. For more information on Symfony Commands, see the 
[Symfony console component documentation](http://symfony.com/doc/current/components/console.html).

## Credits and Contributing
This project was extracted out of the [Yarak](https://github.com/zachleigh/yarak)
project. Many thanks to @micheleangioni for his contributions to that codebase, many
of which have made their way here.

Contributions are more than welcome. Fork, improve and make a pull request. For
bugs, ideas for improvement or other, please create an issue.
