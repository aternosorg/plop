<?php

namespace Aternos\Plop\Structure\Elements;

class Entity extends Element
{
    public function getCommands(): array
    {
        return ["summon " . $this->getName() . " " . $this->getRelativeCoordinatesString() . " " . ($this->nbt ?? "")];
    }
}