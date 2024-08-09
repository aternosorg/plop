<?php

namespace Aternos\Plop;

use Aternos\Plop\Animation\Animation;
use Aternos\Plop\Input\Input;
use Aternos\Plop\Output\MinecraftFunction;
use Aternos\Plop\Output\Output;
use Aternos\Plop\Placement\FullPlacementStrategy;
use Aternos\Plop\Placement\PlacementStrategy;
use Aternos\Plop\Placement\Util\BlockList;
use Aternos\Plop\Structure\Structure;

class Plop
{
    protected Structure $structure;

    public function __construct(
        protected Input $input,
        protected string $functionName = "plop:plop",
        protected ?PlacementStrategy $placementStrategy = null,
        protected ?Output $output = null,
        protected ?string $prefix = null,
        protected ?Animation $defaultAnimation = null,
        protected ?BlockList $blockList = null
    )
    {
        $this->structure = $input->getStructure()
            ->findAndAddStructureVoids()
            ->setDefaultAnimation($defaultAnimation);
    }

    public function generate(): static
    {
        $this->getPlacementStrategy()->setStructure($this->structure)->setBlockList($this->blockList);
        $this->getOutput()->setPlop($this)->generate();
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

    public function getPrefix(): string
    {
        if (!$this->prefix) {
            $this->prefix = "plop_" . str_replace([":", "/"], "_", $this->functionName) . "_";
        }
        return $this->prefix;
    }

    /**
     * @param string|null $prefix
     * @return $this
     */
    public function setPrefix(?string $prefix): static
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @param Animation|null $defaultAnimation
     * @return $this
     */
    public function setDefaultAnimation(?Animation $defaultAnimation): static
    {
        $this->defaultAnimation = $defaultAnimation;
        $this->structure->setDefaultAnimation($defaultAnimation);
        return $this;
    }

    public function getStructure(): Structure
    {
        return $this->structure;
    }

    public function setStructure(Structure $structure): static
    {
        $this->structure = $structure;
        return $this;
    }

    public function getInput(): Input
    {
        return $this->input;
    }

    public function setInput(Input $input): static
    {
        $this->input = $input;
        return $this;
    }

    public function getBlockList(): ?BlockList
    {
        return $this->blockList;
    }

    public function setBlockList(?BlockList $blockList): static
    {
        $this->blockList = $blockList;
        return $this;
    }
}