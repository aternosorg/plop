<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Animation\Animation;

abstract class AnimatableElement extends Element
{
    protected ?Animation $animation = null;

    /**
     * @param Animation|null $animation
     * @return $this
     */
    public function setAnimation(?Animation $animation): static
    {
        $this->animation = $animation;
        return $this;
    }

    public function getAnimation(): ?Animation
    {
        return $this->animation;
    }

}