<?php

namespace Differ\Formatters\Stylish;

function stringify(mixed $value): string
{
    if ($value === true) {
        return 'true';
    }

    if ($value === false) {
        return 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return strval($value);
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
        $indentForImmutableType = str_repeat($replacer, $indentLength);
        $indentForMutableType = str_repeat($replacer, $indentLength - $shiftToLeft);
        $bracketIndent = str_repeat($replacer, $indentLength - $spaceCount);

        $strings = array_map(
            function ($item, $key) use ($indentForImmutableType, $indentForMutableType, $iter, $depth) {
                if (!is_array($item) || !array_key_exists('type', $item)) {
                    return "{$indentForImmutableType}{$key}: {$iter($item, $depth + 1)}";
                }

                if ($item['type'] === 'added') {
                    return "{$indentForMutableType}+ {$item['key']}: {$iter($item['value2'], $depth + 1)}";
                }

                if ($item['type'] === 'deleted') {
                    return "{$indentForMutableType}- {$item['key']}: {$iter($item['value1'], $depth + 1)}";
                }

                if ($item['type'] === 'changed') {
                    $added = "{$indentForMutableType}+ {$item['key']}: {$iter($item['value2'], $depth + 1)}";
                    $deleted = "{$indentForMutableType}- {$item['key']}: {$iter($item['value1'], $depth + 1)}";
                    return "{$deleted}\n{$added}";
                }

                return "{$indentForImmutableType}{$item['key']}: {$iter($item['value1'], $depth + 1)}";
            },
            $currentValue,
            array_keys($currentValue)
        );

        return implode("\n", ["{", ...$strings, "{$bracketIndent}}"]);
    };

    return $iter($value, 1);
}
