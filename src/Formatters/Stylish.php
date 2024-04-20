<?php

namespace Differ\Formatters\Stylish;

function stringify(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return (string) $value;
}

function stringifyTree(mixed $value, string $replacer = ' ', int $spaceCount = 4): string
{
    if (!is_array($value)) {
        return stringify($value);
    }

    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spaceCount) {

        if (!is_array($currentValue)) {
            return stringify($currentValue);
        }

        $indentLength = $spaceCount * $depth;
        $shiftToLeft = 2;
        $indent = str_repeat($replacer, $indentLength - $shiftToLeft);
        $bracketIndent = str_repeat($replacer, $indentLength - $spaceCount);

        $strings = array_map(
            function ($item, $key) use ($indent, $iter, $depth) {
                if (!is_array($item) || !array_key_exists('type', $item)) {
                    return "  {$indent}{$key}: {$iter($item, $depth + 1)}";
                }

                switch ($item['type']) {
                    case 'added':
                        return "{$indent}+ {$item['key']}: {$iter($item['value2'], $depth + 1)}";
                    case 'deleted':
                        return "{$indent}- {$item['key']}: {$iter($item['value1'], $depth + 1)}";
                    case 'changed':
                        $changed = ["{$indent}- {$item['key']}: {$iter($item['value1'], $depth + 1)}",
                        "{$indent}+ {$item['key']}: {$iter($item['value2'], $depth + 1)}"];
                        return implode("\n", $changed);
                    default:
                        return "  {$indent}{$item['key']}: {$iter($item['value1'], $depth + 1)}";
                }
            },
            $currentValue,
            array_keys($currentValue)
        );

        return implode("\n", ["{", ...$strings, "{$bracketIndent}}"]);
    };

    return $iter($value, 1);
}
