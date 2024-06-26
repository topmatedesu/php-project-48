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

function makePlain(array $diff, string $ancestry = ''): string
{
    $plain = array_map(function ($node) use ($ancestry) {

        $key =  $node['key'];
        $propertyName = $ancestry === '' ? $key : "{$ancestry}.{$key}";
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                return makePlain($node['children'], $propertyName);
            case 'added':
                $value = stringify($node['value']);
                return "Property '{$propertyName}' was added with value: {$value}";
            case 'deleted':
                return "Property '{$propertyName}' was removed";
            case 'changed':
                $value1 = stringify($node['value1']);
                $value2 = stringify($node['value2']);
                return "Property '{$propertyName}' was updated. From {$value1} to {$value2}";
            case 'unchanged':
                return '';
            default:
                throw new \Exception("Unknown node type: {$type}");
        }
    }, $diff);

    return implode("\n", array_filter($plain));
}

function render(array $diff): string
{
    return makePlain($diff);
}
