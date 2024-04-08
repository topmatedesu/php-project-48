<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stringifyTree;
use function Differ\Formatters\Plain\stringifyTreeToPlain;

function getArrayComparisonTree(array $array1, array $array2): array
{
    $firstArrayKeys = array_keys($array1);
    $secondArrayKeys = array_keys($array2);
    $keys = array_unique(array_merge($firstArrayKeys, $secondArrayKeys));
    sort($keys, SORT_REGULAR);

    $result = array_map(
        function ($key) use ($array1, $array2) {
            if (
                array_key_exists($key, $array1) && array_key_exists($key, $array2)
                && is_array($array1[$key]) && is_array($array2[$key])
            ) {
                $nestedComparison = getArrayComparisonTree($array1[$key], $array2[$key]);

                return [
                    'key' => $key,
                    'type' => 'nested',
                    'value1' => $nestedComparison,
                    'value2' => $nestedComparison
                ];
            } elseif (!array_key_exists($key, $array2)) {
                return [
                    'key' => $key,
                    'type' => 'deleted',
                    'value1' => $array1[$key],
                    'value2' => null
                ];
            } elseif (!array_key_exists($key, $array1)) {
                return  [
                    'key' => $key,
                    'type' => 'added',
                    'value1' => null,
                    'value2' => $array2[$key]
                ];
            } elseif ($array1[$key] !== $array2[$key]) {
                return  [
                    'key' => $key,
                    'type' => 'updated',
                    'value1' => $array1[$key],
                    'value2' => $array2[$key],
                ];
            } else {
                return [
                    'key' => $key,
                    'type' => 'immutable',
                    'value1' => $array1[$key],
                    'value2' => $array2[$key]
                ];
            }
        },
        $keys
    );

    return $result;
}

function getFormatter(array $dataArray1, array $dataArray2, string $format): string
{
    $result = '';
    $diffArray = getArrayComparisonTree($dataArray1, $dataArray2);

    if ($format === 'stylish') {
        $result = stringifyTree($diffArray);
    }

    if ($format === 'plain') {
        $result = stringifyTreeToPlain($diffArray);
    }

    return $result;
}
