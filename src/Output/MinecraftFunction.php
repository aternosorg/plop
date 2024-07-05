<?php

namespace Aternos\Plop\Output;

use Aternos\Plop\Placement\Placement;
use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Elements\Entity;

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

            if ($element instanceof Entity) {
                $this->function .= $element->getSummonCommand() . PHP_EOL;
            }
        }
        return $this;
    }
}