<?php

namespace Postedin\Docode;

class Analysis
{
    use ImmutableData;

    private $params = [
        'id', 'user', 'repository', 'wordCount', 'suspect', 'suspectFilename', 'coverage',
        'status', 'requestDate', 'responseDate', 'callbackUrl', 'publicUrl', 'siteBlacklist',
    ];

    private $result;

    private $api;

    public function __construct(DocodeApi $api, array $data)
    {
        $this->api = $api;
        $this->data = $data;

        if ($data['result']) {
            $this->result = new Result($data['result']);
        }
    }

    public function analyseWeb()
    {
        return $this->api->analyseWeb($this->id);
    }
}
