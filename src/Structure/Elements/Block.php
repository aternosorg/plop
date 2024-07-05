<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Animation\Animation;

class Block extends Element
{
    protected ?Animation $animation = null;

    public function __construct(string $name, float $x, float $y, float $z, protected array $state = [])
    {
        parent::__construct($name, $x, $y, $z);
    }

    public function getStateString(): string
    {
        $states = [];
        foreach ($this->state as $key => $value) {
            $states[] = $key . "=" . $value;
        }
        return "[" . implode(",", $states) . "]";
    }
}