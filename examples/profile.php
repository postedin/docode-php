<?php

use Postedin\Docode;

require __DIR__ . '../composer/autoload.php';

$api = new Docode\DocodeApi('token here');

// returns an instance of Docode\Profile
$profile = $api->getProfile();
