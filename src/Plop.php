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
        protected ?PlacementStrategy $placementStrategy = null,
        protected ?Output $output = null
    )
    {
        $this->structure = $input->getStructure();
    }

    public function generate(): static
    {
        $this->getPlacementStrategy()->setStructure($this->structure);

        while ($placement = $this->getPlacementStrategy()->getNext()) {
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
}