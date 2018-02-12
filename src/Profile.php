<?php

namespace Postedin\Docode;

class Profile
{
    use ImmutableData;

    private $params = [
        'phone', 'webWords', 'repoWords', 'files', 'username', 'siteBlacklist',
    ];

    private $plan;

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->plan = new Plan($data['regime']);
    }
}
