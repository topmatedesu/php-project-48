<?php

namespace Differ\Formatters\Plain;

function normalizeValue(mixed $value): mixed
{
    if (!is_array($value)) {
        if ($value === 'true' || $value === 'false' || $value === 'null') {
            return $value;
        }

        if (is_numeric($value)) {
            return $value;
        }

        return "'{$value}'";
    }

    return "[complex value]";
}

function stringifyTreeToPlain(array $diffArray, string $parentKey = ''): string
{
    $result = array_map(function ($node) use ($parentKey) {

        $key =  $node['key'];
        $type = $node['type'];
        $value1 = $node['value1'];
        $value2 = $node['value2'];

        $newKey = $parentKey === '' ? $key : $parentKey . '.' . $key;

        switch ($type) {
            case 'nested':
                return stringifyTreeToPlain($value1, $newKey);
            case 'added':
                $normalizeValue = normalizeValue($value2);
                return "Property '" . $newKey . "' was added with value: " . $normalizeValue;
            case 'deleted':
                return "Property '" . $newKey . "' was removed";
            case 'updated':
                $normalizeValue1 = normalizeValue($value1);
                $normalizeValue2 = normalizeValue($value2);
                return "Property '" . $newKey . "' was updated. From " . $normalizeValue1 . ' to ' . $normalizeValue2;
            case 'immutable':
                break;
            default:
                throw new \Exception("Unknown node type: {$type}");
        }
    }, $diffArray);

    $result = array_filter($result);

    return implode("\n", $result);
}
