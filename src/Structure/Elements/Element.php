<?php

namespace Aternos\Plop\Structure\Elements;
class Element
{
    public function __construct(
        protected string $name,
        protected float  $x,
        protected float  $y,
        protected float  $z
    )
    {
    }
}