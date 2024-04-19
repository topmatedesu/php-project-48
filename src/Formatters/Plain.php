<?php

namespace Differ\Formatters\Plain;

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

    if (!is_array($value)) {
        return "'$value'";
    }

    return "[complex value]";
}

function stringifyTreeToPlain(array $diffArray, string $parentKey = ''): string
{
    $plain = array_map(function ($node) use ($parentKey) {

        $key =  $node['key'];
        $newKey = $parentKey === '' ? $key : "{$parentKey}.{$key}";
        $type = $node['type'];

        if ($type === 'nested') {
            $value1 = $node['value1'];

            return stringifyTreeToPlain($value1, $newKey);
        }

        $value1 = stringify($node['value1']);
        $value2 = stringify($node['value2']);

        $types = [
            'added' => "Property '{$newKey}' was added with value: {$value2}",
            'deleted' => "Property '{$newKey}' was removed",
            'changed' => "Property '{$newKey}' was updated. From {$value1} to {$value2}",
            'unchanged' => ''
        ];

        return $types[$type] ?? throw new \Exception("Unknown node type: {$type}");
    }, $diffArray);

    $removeEmptyValues = array_filter($plain);

    return implode("\n", $removeEmptyValues);
}
