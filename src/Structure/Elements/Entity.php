<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Output\TimedCommand;

class Entity extends Element
{
    public function getCommands(string $prefix): array
    {
        return [new TimedCommand("summon " . $this->getName() . " " . $this->getRelativeCoordinatesString() . " " . ($this->nbt ?? ""), positioned: true)];
    }
}