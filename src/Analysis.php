<?php

namespace Postedin\Docode;

class Analysis
{
    use ImmutableData;

    private $params = [
        'id', 'user', 'repository', 'wordCount', 'suspect', 'suspectFilename',
        'status', 'result', 'requestDate', 'responseDate', 'callbackUrl', 'publicUrl',
    ];

    private $api;

    public function __construct(DocodeApi $api, array $data)
    {
        $this->api = $api;
        $this->data = $data;
    }

    public function analyseWeb()
    {
        return $this->api->analyseWeb($this->id);
    }
}
