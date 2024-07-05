<?php

namespace Aternos\Plop;

use Aternos\Plop\Input\Input;
use Aternos\Plop\Output\Output;
use Aternos\Plop\Placement\Placement;

class Plop
{
    protected Output $output;
    protected Placement $placement;

    public function __construct(protected Input $input)
    {
    }
}