<?php

namespace Postedin\Docode;

use LogicException;

trait ImmutableData
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    protected function propertyToData($name): string
    {
        if (ends_with($name, 'Count')) {
            $name = 'n_' . substr($name, 0, -5) . 's';
        }

        return snake_case($name);
    }

    protected function dataToProperty($name): string
    {
        if (starts_with($name, 'n_')) {
            $name = substr(2, trim($name, 's')) . '_count';
        }

        return camel_case($name);
    }

    public function getRawData()
    {
        return $this->data;
    }

    public function __get($property)
    {
        if ($property === 'params') {
            throw new LogicException('The class ' . static::class . ' must have the $params property set when using the ' . __TRAIT__ . ' trait');
        }

        if (in_array($property, $this->params)) {
            $value = $this->data[$this->propertyToData($property)] ?? null;

            if (is_array($value)) {
                $obj = new \stdClass();

                foreach ($value as $k => $v) {
                    $obj->{$this->dataToProperty($k)} = $v;
                }

                return $obj;
            }

            return $value;
        }

        trigger_error('Undefined property: ' . static::class . '::$' . $property, E_USER_NOTICE);
    }
}
