<?php

namespace Aternos\Plop\Output;

use Aternos\Plop\Plop;

abstract class Output
{
    protected Plop $plop;

    abstract public function getAsString(): string;

    abstract public function generate(): static;

    public function save(string $path): bool
    {
        return file_put_contents($path, $this->getAsString()) !== false;
    }

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