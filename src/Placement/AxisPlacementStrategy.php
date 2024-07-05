<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Placement\Util\Axis;
use Aternos\Plop\Placement\Util\AxisStrategy;

class AxisPlacementStrategy extends PlacementStrategy
{
    public function __construct(
        protected array $order = [Axis::X, Axis::Z, Axis::Y],
        protected AxisStrategy $strategy = AxisStrategy::ASCENDING,
        protected ?AxisStrategy $x = null,
        protected ?AxisStrategy $y = null,
        protected ?AxisStrategy $z = null
    )
    {
    }

    public function getPlacements(): array
    {
    }
}