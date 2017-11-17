<?php

use Postedin\Docode;

require __DIR__ . '/../vendor/autoload.php';

$api = new Docode\DocodeApi('token here');

// returns an instance of Docode\Analysis
$analysis = $api->createAnalysis('test-example.txt', 'content')->analyseWeb();

var_dump($analysis);
