# Docode PHP

PHP library which consumes the https://docode.cl API

[![Packagist](https://img.shields.io/packagist/php-v/postedin/docode-php.svg)](https://packagist.org/packages/postedin/docode-php)
[![Packagist](https://img.shields.io/packagist/l/postedin/docode-php.svg)](https://packagist.org/packages/postedin/docode-php)
[![CircleCI](https://circleci.com/gh/postedin/docode-php/tree/master.svg?style=svg)](https://circleci.com/gh/postedin/docode-php/tree/master)

## Installation

https://packagist.org/packages/postedin/docode-php
```
composer require postedin/docode-php
```

## Setup

You need to get your token manually. We don't support getting the token in this package since it requires the **plain text** username and password.  
To get your token make a manual request to the Docode API with that information.

You can do this with `curl` in a terminal:
```bash
curl -X POST -H "Content-Type:application/json" https://docode.cl/app/api/token -d '{"username": "YOUR USERNAME HERE", "password": "YOUR_PASSWORD_HERE"}'
```

Alternatively, we use https://insomnia.rest/ for testing the API and making manual requests.

## Basic Usage
*as taken from the examples directory*

```php
<?php

use Postedin\Docode;

require __DIR__ . '/../vendor/autoload.php';

$api = new Docode\DocodeApi('token here');

$content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas accumsan, purus at dignissim convallis, nisi lacus pellentesque ligula, lobortis ornare lorem est eget nulla. Vestibulum ultrices, erat non consequat tincidunt, dolor justo consectetur nibh, non vehicula risus sem sit amet enim.';

// returns an instance of Docode\Analysis
$analysis = $api->createAnalysis('test-example.txt', $content, 'http://postedin.test/callback-url/')->analyseWeb();

// At this point docode will be analysing your content and when done send results to your callback URL. 
// You also have the data from the request as objects from this library. 

// You can get the results manually by doing...
$newResponseAnalysis = $api->getAnalysis($analysis->id);

// if docode is done this will contain the results, if any
```
