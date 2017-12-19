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
        if ($name == 'wordCount') {
            return isset($this->data['n_words']) ? 'n_words' : 'nWords';
        }

        return snake_case($name);
    }

    protected function dataToProperty($name): string
    {
        if ($name == 'n_words' || $name == 'nWords') {
            return 'wordCount';
        }

        return camel_case($name);
    }

    public function getRawData()
    {
        return $this->data;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        if ($property === 'params') {
            throw new LogicException('The class ' . static::class . ' must have the $params property set when using the ' . __TRAIT__ . ' trait');
        }

        if (in_array($property, $this->params)) {
            $value = $this->data[$this->propertyToData($property)] ?? ($this->data[$property] ?? null);

            if (is_array($value) && $this->isAssoc($value)) {
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

    private function isAssoc($array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }
}
