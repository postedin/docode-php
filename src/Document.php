<?php

namespace Postedin\Docode;

class Document
{
    use ImmutableData;

    private $params = [
        'title', 'text',
    ];
}
