<?php

namespace Aternos\Plop\Structure\Elements;

class Entity extends Element
{
    public function getCommands(string $startIf, string $tagPrefix): array
    {
        return ["execute " . $startIf . " run summon " . $this->getName() . " " . $this->getRelativeCoordinatesString() . " " . ($this->nbt ?? "")];
    }
}