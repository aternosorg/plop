<?php

namespace Aternos\Plop;

use Aternos\Plop\Animation\Animation;
use Aternos\Plop\Animation\DropAnimation;
use Aternos\Plop\Animation\FloatAnimation;
use Aternos\Plop\Animation\GrowAnimation;
use Aternos\Plop\Animation\PlopAnimation;
use Aternos\Plop\Placement\AxisPlacementStrategy;
use Aternos\Plop\Placement\FullPlacementStrategy;
use Aternos\Plop\Placement\PlacementStrategy;
use Aternos\Plop\Placement\PropagationPlacementStrategy;
use Aternos\Plop\Placement\RandomPlacementStrategy;
use Aternos\Plop\Placement\SameMaterialPlacementStrategy;
use Aternos\Plop\Placement\Util\Axis;
use Aternos\Plop\Placement\Util\AxisStrategy;

class Presets
{
    static public function getPlacementStrategyPreset(?string $preset = null): PlacementStrategy
    {
        $strategyString = $preset;
        $parameters = [];
        if ($strategyString && preg_match('/^([\w-]+)\[([^]]*)]$/', $strategyString, $matches)) {
            $strategyString = $matches[1];
            $parameterString = $matches[2];
            foreach (explode(",", $parameterString) as $parameter) {
                $parts = explode("=", $parameter);
                $key = array_shift($parts);
                if (count($parts) === 0) {
                    $parameters[$key] = true;
                } else {
                    $parameters[$key] = implode("=", $parts);
                }
            }
        }

        $strategy = match ($strategyString) {
            "full", null => new FullPlacementStrategy(),
            "random" => new RandomPlacementStrategy(),
            "xzy" => new AxisPlacementStrategy([Axis::X, Axis::Z, Axis::Y]),
            "random-y" => new AxisPlacementStrategy([Axis::X, Axis::Z, Axis::Y], x: AxisStrategy::RANDOM, z: AxisStrategy::RANDOM, perTick: 2),
            "same-material" => new SameMaterialPlacementStrategy(2),
            "propagation" => new PropagationPlacementStrategy(),
            default => throw new \InvalidArgumentException("Unknown placement strategy preset: " . $preset),
        };

        foreach ($parameters as $key => $value) {
            if (property_exists($strategy, $key)) {
                $strategy->$key = $value;
            } else {
                throw new \InvalidArgumentException("Unknown parameter for placement strategy preset: " . $key);
            }
        }

        return $strategy;
    }

    static public function getAnimationPreset(?string $preset = null): ?Animation
    {
        return match ($preset) {
            "none", null => null,
            "plop" => new PlopAnimation(),
            "drop" => new DropAnimation(),
            "grow" => new GrowAnimation(),
            "float" => new FloatAnimation(),
            default => throw new \InvalidArgumentException("Unknown animation preset: " . $preset),
        };
    }
}
