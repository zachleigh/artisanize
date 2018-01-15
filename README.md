# Artisanize
#### Use Laravel Artisan command syntax in any Symfony Console command.
[![Latest Stable Version](https://poser.pugx.org/zachleigh/artisanize/v/stable)](https://packagist.org/packages/zachleigh/artisanize)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](//packagist.org/packages/zachleigh/artisanize)
[![Build Status](https://img.shields.io/travis/zachleigh/artisanize/master.svg)](https://travis-ci.org/zachleigh/artisanize)
[![Quality Score](https://img.shields.io/scrutinizer/g/zachleigh/artisanize.svg)](https://scrutinizer-ci.com/g/zachleigh/artisanize/)
[![StyleCI](https://styleci.io/repos/117339527/shield?style=flat)](https://styleci.io/repos/117339527)     

## Contents
  - [Installation](#installation)

## Installation
Install into a new or existing project that uses Symfony Console via composer:
```
composer require zachleigh/artisanize
```

Then, in your command files, rather than extend Symfony's base command
(Symfony\Component\Console\Command\Command), extend the Artisanize command.
Define `signature` and `description` properties on the method and a `handle` method.
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
