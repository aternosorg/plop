<?php

namespace Aternos\Plop\Output;

use Aternos\Plop\Placement\Placement;
use Aternos\Plop\Structure\Elements\Block;

class MinecraftFunction extends Output
{
    protected string $function = "";
    protected int $maxTick = 0;

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
        $headerPrefix = 'execute unless data storage ' . $this->getStorageName() .  ' {Running:1b} run ';

        $this
            ->add($headerPrefix . $this->createScoreboard($this->getMainScoreBoardName()))
            ->add($headerPrefix . 'execute align xyz run summon minecraft:marker ~ ~ ~ {Tags:["' . $this->getMainEntityTag() . '"]}')
            ->add($headerPrefix . "execute as @e[tag=" . $this->getMainEntityTag() . "] unless score @s " . $this->getMainScoreBoardName() . " matches 0.. run scoreboard players set @s " . $this->getMainScoreBoardName() . " 0")
            ->add($headerPrefix . 'data merge storage ' . $this->getStorageName() . ' {Running:1b}')
            ->add("execute store result storage " . $this->getStorageName() . " Tick int 1 run scoreboard players get @e[tag=" . $this->getMainEntityTag() . ",limit=1] " . $this->getMainScoreBoardName())
            ->doubleLineBreak();

        return $this;
    }

    public function addPlacement(Placement $placement): static
    {
        foreach ($placement->getElements() as $element) {
            $commandList = $element->getCommands($this->plop->getPrefix());
            foreach ($commandList as $command) {
                $tick = $placement->getTick() + $command->getTimeOffset();
                if ($tick > $this->maxTick) {
                    $this->maxTick = $tick;
                }
                $result = 'execute if data storage ' . $this->getStorageName() .  ' {Tick:' . $tick . '} ';
                if ($command->shouldBePositioned()) {
                    $result .= 'at @e[tag=' . $this->getMainEntityTag() . ',limit=1] ';
                }
                $result .= 'run ' . $command->getCommand();
                $this->add($result);
            }
            $this->lineBreak();
        }
        return $this;
    }

    public function generateFooter(): static
    {
        $footerRunningPrefix = 'execute unless data storage ' . $this->getStorageName() .  ' {Finished:1b} run ';
        $footerEndedPrefix = 'execute if data storage ' . $this->getStorageName() .  ' {Finished:1b} run ';

        $this->lineBreak()
            ->add('execute if data storage ' . $this->getStorageName() .  ' {Tick:' . $this->maxTick. '} run data storage merge ' . $this->getStorageName() . ' {Finished:1b}')
            ->add($footerRunningPrefix . 'scoreboard players add @e[tag=' . $this->getMainEntityTag() . '] ' . $this->getMainScoreBoardName() . ' 1')
            ->add($footerRunningPrefix . 'schedule function ' . $this->plop->getFunctionName() . ' 1t')
            ->add($footerEndedPrefix . 'scoreboard objectives remove ' . $this->getMainScoreBoardName())
            ->add($footerEndedPrefix . 'data remove storage ' . $this->getStorageName() . ' Running')
            ->add($footerEndedPrefix . 'data remove storage ' . $this->getStorageName() . ' Tick')
            ->add($footerEndedPrefix . 'data remove storage ' . $this->getStorageName() . ' Finished')
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