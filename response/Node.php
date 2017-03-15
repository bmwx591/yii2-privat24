<?php

namespace bmwx591\privat24\response;


class Node
{
    private $name;
    private $value;
    private $attributes = [];

    public function __construct($name/*, $value, array $attributes*/)
    {
        $this->setName($name);
//        $this->value = $value;
//        $this->attributes = $attributes;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Node name must be set');
        }
        $this->name = $name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }
}
