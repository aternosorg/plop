# Plop
Generate Minecraft function files to make structures plop into existence in Vanilla Minecraft.

![plop](https://github.com/user-attachments/assets/28ec4c33-a8fc-40a9-8a08-7d6471d57ee9)

Plop uses a Vanilla structure file to generate a single function file that works without any other dependencies.

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

The only required argument is the path to your `<input>` structure file.

#### Options

Other available options are:

| Option        | Short | Description                                                                                         |
|---------------|-------|-----------------------------------------------------------------------------------------------------|
| `--function`  | `-f`  | The name of the Minecraft function to generate, e.g. plop:example                                   |
| `--output`    | `-o`  | The output file to save the generated function to, prints output to terminal if not set.            |
| `--animation` | `-a`  | The animation preset to use, e.g. 'plop'. Defaults to no animation.                                 |
| `--placement` | `-p`  | The placement strategy preset to use, e.g. 'xzy'. Defaults to placing everything at once or 'full'. |
| `--blocks`    | `-b`  | List of blocks to filter for, start your list with ~ to use all blocks except the listed blocks.    |

#### Animations
An animation defines how individual blocks appear usually using block entities. 
You can find all available animations and parameters here: [wiki/Animations](https://github.com/aternosorg/plop/wiki/Animations)

#### Placement strategies
A placement strategy defines in which order and at which speed blocks of the structure are added to the world.
You can find all available placement strategies and parameters here: [wiki/Placement Strategies](https://github.com/aternosorg/plop/wiki/Placement-Strategies)

### Library

Using Plop as a library allows much greater control over the generation process.
It is also possible to extend Plop to support custom animation types and placement strategies.

```php

$input = \Aternos\Plop\Input\StructureFile\StructureFile::loadFile("something.nbt");
$strategy = new \Aternos\Plop\Placement\SameMaterialPlacementStrategy(perTick: 3);
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
