<?php

namespace Differ\Formatters\Stylish;

function stringify(mixed $value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (!is_array($value) && !is_object($value)) {
        return (string) $value;
    }

    $arrayOfStrings = array_map(
        function ($value, $key) use ($depth) {
            $indent = buildIndent($depth + 1);
            $value = stringify($value, $depth + 1);
            return "{$indent}{$key}: {$value}";
        },
        $value,
        array_keys($value)
    );
    $indentEnd = buildIndent($depth);
    $string = implode("\n", $arrayOfStrings);

    return "{\n{$string}\n{$indentEnd}}";
}

function buildIndent(int $depth = 1, int $shift = 0, int $spaceCount = 4): string
{
    return str_repeat(' ', $spaceCount * $depth - $shift);
}

function makeStylish(array $diff, int $depth = 1): string
{
    $stylish = array_map(
        function ($node) use ($depth) {
            $key = $node['key'];
            $type = $node['type'];
            $indent = buildIndent($depth);
            $smallIndent = buildIndent($depth, 2);

            switch ($type) {
                case 'added':
                    $value = stringify($node['value'], $depth);
                    return "{$smallIndent}+ {$key}: {$value}";
                case 'deleted':
                    $value = stringify($node['value'], $depth);
                    return "{$smallIndent}- {$key}: {$value}";
                case 'changed':
                    $value1 = stringify($node['value1'], $depth);
                    $value2 = stringify($node['value2'], $depth);
                    $changed = ["{$smallIndent}- {$key}: {$value1}",
                    "{$smallIndent}+ {$key}: {$value2}"];
                    return implode("\n", $changed);
                case 'unchanged':
                    $value = stringify($node['value'], $depth);
                    return "{$indent}{$key}: {$value}";
                case 'nested':
                    $children = makeStylish($node['children'], $depth + 1);
                    return "{$indent}{$key}: {\n{$children}\n{$indent}}";
                default:
                    throw new \Exception('Unknown node type');
            }
        },
        $diff
    );

    return implode("\n", $stylish);
}

function render(array $diff): string
{
    $stylish = makeStylish($diff);

    return "{\n{$stylish}\n}";
}
