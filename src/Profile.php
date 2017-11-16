<?php

namespace Postedin\Docode;

class Profile
{
    private $params = [
        'maxWords', 'maxFiles', 'words', 'files', 'phone',
        'avatar', 'regime',
    ];

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __get($property)
    {
        if (in_array($property, $this->params)) {
            return $this->data[snake_case($property)] ?? null;
        }

        trigger_error('Undefined property: ' . static::class . '::$' . $property, E_USER_NOTICE);
    }
}
