<?php

namespace Aternos\Plop\Output;

class TimedCommand
{
    public function __construct(
        protected string $command,
        protected int $timeOffset = 0,
        protected bool $positioned = false)
    {
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setCommand(string $command): static
    {
        $this->command = $command;
        return $this;
    }

    public function getTimeOffset(): int
    {
        return $this->timeOffset;
    }

    public function setTimeOffset(int $timeOffset): static
    {
        $this->timeOffset = $timeOffset;
        return $this;
    }

    public function shouldBePositioned(): bool
    {
        return $this->positioned;
    }

    public function setPositioned(bool $positioned): static
    {
        $this->positioned = $positioned;
        return $this;
    }
}