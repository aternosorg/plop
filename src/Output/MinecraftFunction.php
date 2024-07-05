<?php

namespace Aternos\Plop\Output;

use Aternos\Plop\Placement\Placement;

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
            $this->function .= implode(PHP_EOL, $element->getCommands()) . PHP_EOL . PHP_EOL;
        }
        return $this;
    }
}