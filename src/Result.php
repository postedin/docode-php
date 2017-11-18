<?php

namespace Postedin\Docode;

class Result
{
    use ImmutableData;

    private $params = [
        'suspectContainment', 'wordCount',
    ];

    private $suspect;

    private $sources;

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->suspect = new Document($data['suspect']);

        $this->sources = array_map(function ($source) {
            return new Source($source);
        }, $data['sources']);
    }
}
