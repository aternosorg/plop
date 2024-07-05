<?php

namespace Aternos\Plop\Output;

use Aternos\Plop\Placement\Placement;
use Aternos\Plop\Structure\Elements\Block;

class MinecraftFunction extends Output
{
    protected string $function = "";
    /**
     * @var Placement[]
     */
    protected array $placements = [];

    public function getAsString(): string
    {
        return $this->function;
    }

    public function generate(): static
    {
        $this->function = "";
        $this->placements = $this->plop->getPlacementStrategy()->getPlacements();

        $this->generateHeader();
        foreach ($this->placements as $placement) {
            $this->addPlacement($placement);
        }
        $this->generateFooter();

        return $this;
    }

    public function generateHeader(): static
    {
        $headerPrefix = 'execute unless entity @e[tag=' . $this->getMainEntityTag() . '] run ';

        $this
            ->add($headerPrefix . $this->createScoreboard($this->getMainScoreBoardName()))
            ->add($headerPrefix . $this->createScoreboard($this->getBlockEntityScoreBoardName()))
            ->add($headerPrefix . 'summon minecraft:marker ~ ~ ~ {Tags:["' . $this->getMainEntityTag() . '"]}')
            ->doubleLineBreak();

        return $this;
    }

    public function addPlacement(Placement $placement): static
    {
        foreach ($placement->getElements() as $element) {
            $startIf = 'if score @e[tag=' . $this->getMainEntityTag() . ',limit=1] ' . $this->getMainScoreBoardName() . ' matches ' . $placement->getTick();
            $this->function .= implode(PHP_EOL, $element->getCommands($startIf, $this->plop->getPrefix())) . PHP_EOL . PHP_EOL;
        }
        return $this;
    }

    public function generateFooter(): static
    {
        $footerRunningPrefix = 'execute if entity @e[tag=' . $this->getMainEntityTag() . '] run ';
        $footerEndedPrefix = 'execute unless entity @e[tag=' . $this->getMainEntityTag() . '] run ';

        $this->lineBreak()
            ->add('execute unless entity @e[tag=' . $this->getBlockEntityTag() . '] if score @e[tag=' . $this->getMainEntityTag() . ',limit=1] ' . $this->getMainScoreBoardName() . ' matches ' . $this->getMaxTick() . '.. run kill @e[tag=' . $this->getMainEntityTag() . ']')
            ->add($footerRunningPrefix . 'scoreboard players add @e[tag=' . $this->getMainEntityTag() . '] ' . $this->getMainScoreBoardName() . ' 1')
            ->add($footerRunningPrefix . 'scoreboard players add @e[tag=' . $this->getBlockEntityTag() . '] ' . $this->getBlockEntityScoreBoardName() . ' 1')
            ->add($footerEndedPrefix . 'scoreboard objectives remove ' . $this->getMainScoreBoardName())
            ->add($footerEndedPrefix . 'scoreboard objectives remove ' . $this->getBlockEntityScoreBoardName())
            ->lineBreak()
            ->add($footerRunningPrefix . 'schedule function ' . $this->plop->getFunctionName() . ' 1t');

        return $this;
    }

    protected function add(string $function): static
    {
        $this->function .= $function . PHP_EOL;
        return $this;
    }

    protected function lineBreak(): static
    {
        $this->function .= PHP_EOL;
        return $this;
    }

    protected function doubleLineBreak(): static
    {
        $this->function .= PHP_EOL . PHP_EOL;
        return $this;
    }

    protected function getMaxTick(): int
    {
        $maxTick = 0;
        foreach ($this->placements as $placement) {
            $tick = $placement->getTick();
            if ($tick > $maxTick) {
                $maxTick = $tick;
            }
        }
        return $maxTick;
    }

    protected function getMainEntityTag(): string
    {
        return $this->plop->getPrefix() . "main";
    }

    protected function getMainScoreBoardName(): string
    {
        return $this->getMainEntityTag();
    }

    protected function getBlockEntityTag(): string
    {
        return $this->plop->getPrefix() . Block::TAG;
    }

    protected function getBlockEntityScoreBoardName(): string
    {
        return $this->getBlockEntityTag();
    }

    protected function createScoreboard(string $name): string
    {
        return 'scoreboard objectives add ' . $name . ' dummy';
    }

}