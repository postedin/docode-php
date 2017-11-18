<?php

namespace Postedin\Docode;

class Source
{
    use ImmutableData;

    private $params = [
        'suspectContainment',
    ];

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->document = new Document($data['document']);

        $this->matches = array_map(function ($match) {
            return new Match($match);
        }, $data['matches']);
    }
}
