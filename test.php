<?php

require_once 'vendor/autoload.php';

$structure = \Aternos\Plop\Input\StructureFile\StructureFile::loadFile("/home/kurt/Downloads/silo.nbt");

var_dump($structure);
