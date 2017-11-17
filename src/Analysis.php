<?php

namespace Postedin\Docode;

class Analysis
{
    use ImmutableData;

    private $params = [
        'id', 'user', 'repository', 'wordCount', 'suspect', 'suspectFilename',
        'status', 'requestDate', 'responseDate', 'callbackUrl', 'publicUrl',
    ];

    private $result;

    private $api;

    public function __construct(DocodeApi $api, array $data)
    {
        $this->api = $api;
        $this->data = $data;

        $this->result = new Result($data['result']);
    }

    public function analyseWeb()
    {
        return $this->api->analyseWeb($this->id);
    }
}
