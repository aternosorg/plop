<?php

namespace Aternos\Plop\Output;

use Aternos\Plop\Placement\Placement;

abstract class Output
{
    abstract public function getAsString(): string;

    abstract public function addPlacement(Placement $placement): static;

    public function save(string $path): bool
    {
        return file_put_contents($path, $this->getAsString()) !== false;
    }
}