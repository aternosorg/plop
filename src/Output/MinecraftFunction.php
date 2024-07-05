<?php

namespace Aternos\Plop\Output;

use Aternos\Plop\Placement\Placement;
use Aternos\Plop\Structure\Elements\Block;

class MinecraftFunction extends Output
{
    protected string $function = "";

    public function getAsString(): string
    {
        return $this->function;
    }

    public function addPlacement(Placement $placement): static
    {
        foreach ($placement->getElements() as $element) {
            if ($element instanceof Block) {
                $this->function .= $element->getSetBlockCommand() . PHP_EOL;
            }
        }
        return $this;
    }
}