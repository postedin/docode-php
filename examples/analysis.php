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
