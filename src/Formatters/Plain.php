<?php

namespace Differ\Formatters\Plain;

function stringify(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_array($value) || is_object($value)) {
        return '[complex value]';
    }

    if (is_string($value)) {
        return "'$value'";
    }

    return (string) $value;
}

function stringifyTreeToPlain(array $diffArray, string $ancestry = ''): string
{
    $plain = array_map(function ($node) use ($ancestry) {

        $key =  $node['key'];
        $newKey = $ancestry === '' ? $key : "{$ancestry}.{$key}";
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
