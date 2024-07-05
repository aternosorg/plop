<?php

class Block extends Element
{
    public function __construct(string $name, float $x, float $y, float $z, protected array $state = [])
    {
        parent::__construct($name, $x, $y, $z);
    }
}