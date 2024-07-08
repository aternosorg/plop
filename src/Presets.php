<?php

namespace Aternos\Plop;

use Aternos\Plop\Animation\Animation;
use Aternos\Plop\Animation\DropAnimation;
use Aternos\Plop\Animation\GrowAnimation;
use Aternos\Plop\Animation\PlopAnimation;
use Aternos\Plop\Placement\AxisPlacementStrategy;
use Aternos\Plop\Placement\FullPlacementStrategy;
use Aternos\Plop\Placement\PlacementStrategy;
use Aternos\Plop\Placement\RandomPlacementStrategy;
use Aternos\Plop\Placement\SameMaterialPlacementStrategy;
use Aternos\Plop\Placement\Util\Axis;
use Aternos\Plop\Placement\Util\AxisStrategy;

class Presets
{
    static public function getPlacementStrategyPreset(?string $preset = null): PlacementStrategy
    {
        return match ($preset) {
            "full", null => new FullPlacementStrategy(),
            "random" => new RandomPlacementStrategy(),
            "xzy" => new AxisPlacementStrategy([Axis::X, Axis::Z, Axis::Y]),
            "random-y" => new AxisPlacementStrategy([Axis::X, Axis::Z, Axis::Y], x: AxisStrategy::RANDOM, z: AxisStrategy::RANDOM, elementsPerTick: 2),
            "same-material" => new SameMaterialPlacementStrategy(2),
            default => throw new \InvalidArgumentException("Unknown placement strategy preset: " . $preset),
        };
    }

    static public function getAnimationPreset(?string $preset = null): ?Animation
    {
        return match ($preset) {
            "none", null => null,
            "plop" => new PlopAnimation(),
            "drop" => new DropAnimation(),
            "grow" => new GrowAnimation(),
            default => throw new \InvalidArgumentException("Unknown animation preset: " . $preset),
        };
    }
}
