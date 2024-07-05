<?php

namespace Aternos\Plop;

use Aternos\Plop\Input\Input;
use Aternos\Plop\Output\MinecraftFunction;
use Aternos\Plop\Output\Output;
use Aternos\Plop\Placement\FullPlacementStrategy;
use Aternos\Plop\Placement\PlacementStrategy;
use Aternos\Plop\Structure\Structure;

class Plop
{
    protected Structure $structure;

    public function __construct(
        protected Input $input,
        protected string $functionName = "plop:plop",
        protected ?PlacementStrategy $placementStrategy = null,
        protected ?Output $output = null,
        protected ?string $tagPrefix = null
    )
    {
        $this->structure = $input->getStructure();
    }

    public function generate(): static
    {
        $this->getPlacementStrategy()->setStructure($this->structure);
        $this->getOutput()->setPlop($this);

        foreach ($this->getPlacementStrategy()->getPlacements() as $placement) {
            $this->getOutput()->addPlacement($placement);
        }

        return $this;
    }

    /**
     * @param PlacementStrategy|null $placementStrategy
     * @return $this
     */
    public function setPlacementStrategy(?PlacementStrategy $placementStrategy): static
    {
        $this->placementStrategy = $placementStrategy;
        return $this;
    }


    public function getPlacementStrategy(): PlacementStrategy
    {
        return $this->placementStrategy ?? $this->placementStrategy = new FullPlacementStrategy();
    }

    /**
     * @param Output|null $output
     * @return $this
     */
    public function setOutput(?Output $output): static
    {
        $this->output = $output;
        return $this;
    }

    public function getOutput(): ?Output
    {
        return $this->output ?? $this->output = new MinecraftFunction();
    }

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    public function getTagPrefix(): string
    {
        if (!$this->tagPrefix) {
            $this->tagPrefix = "plop_" . str_replace(":", "_", $this->functionName) . "_";
        }
        return $this->tagPrefix;
    }

    /**
     * @param string|null $tagPrefix
     * @return $this
     */
    public function setTagPrefix(?string $tagPrefix): static
    {
        $this->tagPrefix = $tagPrefix;
        return $this;
    }
}