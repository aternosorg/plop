<?php

namespace Aternos\Plop\Structure\Elements;

class ElementCommandList
{
    public function __construct(
        protected array $startCommands = [],
        protected array $runCommands = []
    )
    {
    }

    public function addStartCommand(string $command): static
    {
        $this->startCommands[] = $command;
        return $this;
    }

    public function addRunCommand(string $command): static
    {
        $this->runCommands[] = $command;
        return $this;
    }

    public function getStartCommands(): array
    {
        return $this->startCommands;
    }

    public function setStartCommands(array $startCommands): static
    {
        $this->startCommands = $startCommands;
        return $this;
    }

    public function getRunCommands(): array
    {
        return $this->runCommands;
    }

    public function setRunCommands(array $runCommands): static
    {
        $this->runCommands = $runCommands;
        return $this;
    }


}