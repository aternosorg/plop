<?php

class Structure
{
    protected array $elements = [];

    public function addElement(Element $element): void
    {
        $this->elements[] = $element;
    }
}