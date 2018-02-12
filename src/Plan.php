<?php

namespace Postedin\Docode;

class Plan
{
    use ImmutableData;

    private $params = [
        'webWords', 'repoWords', 'files', 'isExpired',
    ];
}
