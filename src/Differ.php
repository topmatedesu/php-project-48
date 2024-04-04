<?php

namespace Differ\Differ;

use function Differ\Parsers\fileDecode;
use function Differ\Stylish\stringifyTree;

function getArrayComparisonTree(array $array1, array $array2): array
{
    $result = [];
    $firstArrayKeys = array_keys($array1);
    $secondArrayKeys = array_keys($array2);
    $keys = array_unique(array_merge($firstArrayKeys, $secondArrayKeys));
    sort($keys, SORT_REGULAR);

    foreach ($keys as $key) {
        if (
            array_key_exists($key, $array1) && array_key_exists($key, $array2)
            && is_array($array1[$key]) && is_array($array2[$key])
        ) {
            $nestedComparison = getArrayComparisonTree($array1[$key], $array2[$key]);

            $result[] = [
                'key' => $key,
                'type' => 'immutable',
                'value' => $nestedComparison,
            ];
        } elseif (!array_key_exists($key, $array2)) {
            $result[] = [
                'key' => $key,
                'type' => 'deleted',
                'value' => $array1[$key],
            ];
        } elseif (!array_key_exists($key, $array1)) {
            $result[] = [
                'key' => $key,
                'type' => 'added',
                'value' => $array2[$key],
            ];
        } elseif ($array1[$key] !== $array2[$key]) {
            $result[] = [
                'key' => $key,
                'type' => 'deleted',
                'value' => $array1[$key],
            ];
            $result[] = [
                'key' => $key,
                'type' => 'added',
                'value' => $array2[$key],
            ];
        } else {
            $result[] = [
                'key' => $key,
                'type' => 'immutable',
                'value' => $array1[$key],
            ];
        }
    }

    return $result;
}

function genDiff(string $firstFilePath, string $secondFilePath, string $format = 'stylish'): string
{

    $firstData = fileDecode($firstFilePath);
    $secondData = fileDecode($secondFilePath);

    $diffArray = getArrayComparisonTree($firstData, $secondData);
    $result = '';

    if ($format === 'stylish') {
        $result = stringifyTree($diffArray);
    }

    return $result . "\n";
}
