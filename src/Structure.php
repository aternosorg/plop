<?php

class Structure
{
    protected array $elements = [];

    public function __construct(
        protected int $sizeX,
        protected int $sizeY,
        protected int $sizeZ
    )
    {
    }

    public function addElement(Element $element): void
    {
        $this->elements[] = $element;
    }
}