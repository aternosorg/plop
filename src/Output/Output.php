<?php

namespace Aternos\Plop\Output;

use Aternos\Plop\Placement\Placement;
use Aternos\Plop\Plop;

abstract class Output
{
    protected Plop $plop;

    abstract public function getAsString(): string;

    abstract public function addPlacement(Placement $placement): static;

    public function save(string $path): bool
    {
        return file_put_contents($path, $this->getAsString()) !== false;
    }

    abstract public function generateHeader(): static;

    abstract public function generateFooter(): static;

    /**
     * @param Plop $plop
     * @return $this
     */
    public function setPlop(Plop $plop): static
    {
        $this->plop = $plop;
        return $this;
    }
}