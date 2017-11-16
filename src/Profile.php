<?php

namespace Postedin\Docode;

class Profile
{
    private $params = [
        'words', 'files', 'phone', 'username', 'regime',
    ];

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __get($property)
    {
        if (in_array($property, $this->params)) {
            $value = $this->data[snake_case($property)] ?? null;

            if (is_array($value)) {
                $obj = new \stdClass();

                foreach ($value as $k => $v) {
                    $obj->{camel_case($k)} = $v;
                }

                return $obj;
            }

            return $value;
        }

        trigger_error('Undefined property: ' . static::class . '::$' . $property, E_USER_NOTICE);
    }
}
