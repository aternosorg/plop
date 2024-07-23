# Plop
Generate Minecraft function files to make structures plop into existence.

[This could be a gif]

## Installation
### CLI
```bash
git clone https://github.com/aternosorg/plop.git
cd plop
composer install
```

### Library
```bash
composer require aternos/plop
```

## Usage

Plop generates its function files from Minecraft structure files.
These files can be created using the structure block in Minecraft: https://minecraft.wiki/w/Structure_Block

### CLI

Plop includes a simple CLI to generate Minecraft function files using a handful of preset animation types.

```bash
php plop.php <input> [--function|-f <function>] [--output|-o <output>] [--animation|-a <animation>] [--placement|-p <placement>]
```

#### Options

| Option        |      | Description                                                                                         |
|---------------|------|-----------------------------------------------------------------------------------------------------|
| `--function`  | `-f` | The name of the Minecraft function to generate, e.g. plop:example                                   |
| `--output`    | `-o` | The output file to save the generated function to, outputs to STDOUT if not set                     |
| `--animation` | `-a` | The animation preset to use, e.g. 'plop'. Defaults to no animation.                                 |
| `--placement` | `-p` | The placement strategy preset to use, e.g. 'xzy'. Defaults to placing everything at once or 'full'. |
| `--blocks`    | `-b` | List of blocks to filter for, start your list with ! to disallow this list of blocks.               |

Available animation presets are `none`, `plop`, `drop`, `grow`, and `float`.  
Available placement strategy presets are `full`, `random`, `xzy`, `random-y` and `same-material`.  

To customize placement strategies and animations, use the library directly.

### Library

Using Plop as a library allows much greater control over the generation process.
It is also possible to extend Plop to support custom animation types and placement strategies.

```php

$input = \Aternos\Plop\Input\StructureFile\StructureFile::loadFile("something.nbt");
$strategy = new \Aternos\Plop\Placement\SameMaterialPlacementStrategy(elementsPerTick: 3);
$animation = new \Aternos\Plop\Animation\FloatAnimation(animationDuration: 25, x: -10, y: 6, z: -10);

$plop = new \Aternos\Plop\Plop(
    input: \Aternos\Plop\Input\StructureFile\StructureFile::loadFile("something.nbt"),
    functionName: "plop:example",
    placementStrategy: $strategy,
    defaultAnimation: $animation
);
$plop->generate();

echo $plop->getOutput()->getAsString();
```

### Placement strategies

A placement strategy defines in which order and at which speed
blocks of the structure are added to the world. 

Built-in placement strategies are:
 - `FullPlacementStrategy`: Places all blocks at once.
 - `AxisPlacementStrategy`: Grows the structure along the specified axis (x, y, z) as defined by the `AxisStrategy`.
 - `RandomPlacementStrategy`: Places blocks randomly.
 - `SameMaterialPlacementStrategy`: Places blocks grouped by block/material type.

Additional placement strategies can be implemented by extending the `PlacementStrategy` class.

### Animation

An animation defines how individual blocks are moved into place.
Setting an animation is optional, in which case no animation is used.

Built-in animations are:
 - `PlopAnimation`: Blocks spawn in the center of their target location while being tiny and grow to full size.
 - `DropAnimation`: Blocks drop from above into place.
 - `GrowAnimation`: Blocks grow from the bottom to the top.
 - `FloatAnimation`: Blocks spawn at the origin point and float to their target location.

Additional animations can be implemented by extending the `Animation` class.
