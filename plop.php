<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('ARGUMENTS_START')) {
    define('ARGUMENTS_START', 2);
}

const SHORT = [
    'f' => 'function',
    'o' => 'output',
    'a' => 'animation',
    'p' => 'placement',
    'b' => 'blocks'
];
$options = ["function" => "plop:example", "output" => null, "animation" => null, "placement" => null, "blocks" => null];
$arguments = ["input" => null];
$currentOption = null;
$currentArgument = 0;

if (count($argv) <= ARGUMENTS_START) {
    echo "Usage: php plop.php <input> [--function|-f <function>] [--output|-o <output>] [--animation|-a <animation>] [--placement|-p <placement>] [--blocks|-b <blocks>]" . PHP_EOL . PHP_EOL;
    echo "Options:" . PHP_EOL;
    echo "  --function, -f <function>    The Minecraft function name to generate, e.g. plop:example" . PHP_EOL;
    echo "  --output, -o <output>        The output file to save the generated function to, prints output to terminal if not set." . PHP_EOL;
    echo "  --animation, -a <animation>  The animation preset to use, e.g. 'plop'. Defaults to no animation." . PHP_EOL;
    echo "  --placement, -p <placement>  The placement strategy preset to use, e.g. 'xzy'. Defaults to placing everything at once or 'full'." . PHP_EOL;
    echo "  --blocks, -b <blocks>        List of blocks to filter for, start your list with ~ to use all blocks except the listed blocks." . PHP_EOL;
    exit(1);
}

function fatalError($message): void
{
    echo "\033[0;31mError: " . $message . "\033[0m" . PHP_EOL;
    exit(1);
}

// simple argument / option parsing
foreach ($argv as $i => $arg) {
    if ($i < ARGUMENTS_START) {
        continue;
    }

    if ($currentOption !== null) {
        $options[$currentOption] = $arg;
        $currentOption = null;
        continue;
    }

    if (str_starts_with($arg, "--")) {
        $option = substr($arg, 2);
        if (!array_key_exists($option, $options)) {
            fatalError("Unknown option: --" . $option);
        }
        $currentOption = $option;
        continue;
    }

    if (str_starts_with($arg, "-")) {
        $short = substr($arg, 1);
        if (!array_key_exists($short, SHORT)) {
            fatalError("Unknown short option: -" . $short);
        }
        $currentOption = SHORT[$short];
        continue;
    }

    if ($currentArgument >= count($arguments)) {
        fatalError("Too many arguments");
    }

    $arguments[array_keys($arguments)[$currentArgument]] = $arg;
    $currentArgument++;
}

if ($arguments["input"] === null) {
    fatalError("No input file specified");
}


try {
    $plop = new \Aternos\Plop\Plop(
        input: \Aternos\Plop\Input\StructureFile\StructureFile::loadFile($arguments["input"]),
        functionName: $options["function"],
        placementStrategy: \Aternos\Plop\Presets::getPlacementStrategyPreset($options["placement"]),
        defaultAnimation: \Aternos\Plop\Presets::getAnimationPreset($options["animation"]),
        blockList: \Aternos\Plop\Placement\Util\BlockList::fromString($options["blocks"])
    );
    $plop->generate();
} catch (InvalidArgumentException $e) {
    fatalError($e->getMessage());
}

if ($options["output"] !== null) {
    $plop->getOutput()->save($options["output"]);
} else {
    echo $plop->getOutput()->getAsString();
}