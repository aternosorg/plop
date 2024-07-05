<?php

namespace Aternos\Plop\Structure\Elements;

class Entity extends Element
{
    public function getCommands(string $prefix): ElementCommandList
    {
        return new ElementCommandList(["summon " . $this->getName() . " " . $this->getRelativeCoordinatesString() . " " . ($this->nbt ?? "")]);
    }
}
