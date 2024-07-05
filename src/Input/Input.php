<?php

namespace Aternos\Plop\Input;

use Aternos\Plop\Structure\Structure;

abstract class Input
{
    abstract public function getStructure(): Structure;
}