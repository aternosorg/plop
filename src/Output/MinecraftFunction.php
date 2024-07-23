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
            ->add($headerPrefix . 'execute align xyz run summon minecraft:marker ~ ~ ~ {Tags:["' . $this->getMainEntityTag() . '"]}')
            ->add("execute as @e[tag=" . $this->getMainEntityTag() . "] unless score @s " . $this->getMainScoreBoardName() . " matches 0.. run scoreboard players set @s " . $this->getMainScoreBoardName() . " 0")
            ->add("execute store result storage " . $this->getStorageName() . " Tick int 1 run scoreboard players get @e[tag=" . $this->getMainEntityTag() . ",limit=1] " . $this->getMainScoreBoardName())
            ->doubleLineBreak();

        return $this;
    }

    public function addPlacement(Placement $placement): static
    {
        foreach ($placement->getElements() as $element) {
            $asEntity = 'as @e[tag=' . $this->getMainEntityTag() . ',limit=1] at @s ';
            $startIf = 'if data storage ' . $this->getStorageName() .  ' {Tick:' . $placement->getTick() . '} ';

            $commandList = $element->getCommands($this->plop->getPrefix(), $placement->getTick());
            foreach ($commandList->getStartCommands() as $command) {
                $this->add("execute " . $startIf . $asEntity . "run " . $command);
            }
            foreach ($commandList->getRunCommands() as $command) {
                $this->add($command);
            }
            $this->lineBreak();
        }
        return $this;
    }

    public function generateFooter(): static
    {
        $footerRunningPrefix = 'execute if entity @e[tag=' . $this->getMainEntityTag() . ',tag=!' . $this->getFinishedEntityTag() . '] run ';
        $footerEndedPrefix = 'execute if entity @e[tag=' . $this->getMainEntityTag() . ',tag=' . $this->getFinishedEntityTag() . '] run ';

        $this->lineBreak()
            ->add('execute unless entity @e[tag=' . $this->getBlockEntityTag() . '] if score @e[tag=' . $this->getMainEntityTag() . ',limit=1] ' . $this->getMainScoreBoardName() . ' matches ' . $this->getMaxTick() . '.. run tag @e[tag=' . $this->getMainEntityTag() . '] add ' . $this->getFinishedEntityTag())
            ->add($footerRunningPrefix . 'scoreboard players add @e[tag=' . $this->getMainEntityTag() . '] ' . $this->getMainScoreBoardName() . ' 1')
            ->add($footerRunningPrefix . 'schedule function ' . $this->plop->getFunctionName() . ' 1t')
            ->add($footerEndedPrefix . 'scoreboard objectives remove ' . $this->getMainScoreBoardName())
            ->add($footerEndedPrefix . 'data remove storage ' . $this->getStorageName() . ' Tick')
            ->add($footerEndedPrefix. 'kill @e[tag=' . $this->getMainEntityTag() . ']');

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

    protected function getFinishedEntityTag(): string
    {
        return $this->plop->getPrefix() . "finished";
    }

    protected function getMainScoreBoardName(): string
    {
        return $this->getMainEntityTag();
    }

    protected function getStorageName(): string
    {
        return "plop:" . $this->plop->getPrefix() . "storage";
    }

    protected function getBlockEntityTag(): string
    {
        return $this->plop->getPrefix() . Block::TAG;
    }

    protected function createScoreboard(string $name): string
    {
        return 'scoreboard objectives add ' . $name . ' dummy';
    }

}