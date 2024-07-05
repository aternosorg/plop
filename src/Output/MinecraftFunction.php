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

    public function getMainEntityTag(): string
    {
        return $this->plop->getPrefix() . "main";
    }

    public function getBlockEntityTag(): string
    {
        return $this->plop->getPrefix() . Block::TAG;
    }

    public function generateHeader(): static
    {
        $prefix = $this->plop->getPrefix();
        return $this;
    }

    public function addPlacement(Placement $placement): static
    {
        foreach ($placement->getElements() as $element) {
            $this->function .= implode(PHP_EOL, $element->getCommands("", $this->plop->getPrefix())) . PHP_EOL . PHP_EOL;
        }
        return $this;
    }

    public function generateFooter(): static
    {
        return $this;
    }
}