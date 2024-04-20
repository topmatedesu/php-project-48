<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;
use function Functional\sort;

function genDiff(string $filePath1, string $filePath2, string $format = 'stylish'): string
{
    $rawData1 = getRawData($filePath1);
    $rawData2 = getRawData($filePath2);

    $data1 = parse($rawData1, getExtension($filePath1));
    $data2 = parse($rawData2, getExtension($filePath2));

    $diff = buildDiffTree($data1, $data2);

    return format($diff, $format);
}

function getRealPath(string $filePath): string
{
    $fullPath = realpath($filePath);
    if ($fullPath === false) {
        throw new \Exception("File {$filePath} does not exist");
    }

    return $fullPath;
}

function getRawData(string $filePath): string
{
    $fullPath = getRealPath($filePath);
    $data = file_get_contents($fullPath);

    if ($data === false) {
        throw new \Exception("Can't read file");
    }

    return $data;
}

function getExtension(string $filePath): string
{
    return pathinfo($filePath, PATHINFO_EXTENSION);
}

function buildDiffTree(array $data1, array $data2): array
{
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);
    $keys = array_unique(array_merge($keys1, $keys2));
    $sortedKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    return array_map(
        function ($key) use ($data1, $data2) {
            $value1 = $data1[$key] ?? null;
            $value2 = $data2[$key] ?? null;

            if (!array_key_exists($key, $data2)) {
                return [
                    'key' => $key,
                    'type' => 'deleted',
                    'value' => $value1
                ];
            }

            if (!array_key_exists($key, $data1)) {
                return [
                    'key' => $key,
                    'type' => 'added',
                    'value' => $value2
                ];
            }

            if (is_array($value1) && is_array($value2)) {
                return [
                    'key' => $key,
                    'type' => 'nested',
                    'children' => buildDiffTree($value1, $value2)
                ];
            }

            if ($value1 !== $value2) {
                return [
                    'key' => $key,
                    'type' => 'changed',
                    'value1' => $value1,
                    'value2' => $value2,
                ];
            }

            return [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $value1
            ];
        },
        $sortedKeys
    );
}
