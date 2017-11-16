<?php

namespace Postedin\Docode;

class Analysis
{
    use ImmutableData;

    private $params = [
        'id', 'user', 'repository', 'wordCount', 'suspect', 'suspectFilename',
        'status', 'result', 'requestDate', 'responseDate', 'callbackUrl', 'publicUrl',
    ];
}
