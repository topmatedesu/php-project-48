<?php

namespace Differ\Differ;

use function Differ\Parsers\parseData;
use function Differ\Formatters\getFormatter;
use function Functional\sort;

function getRealPath(string $filePath): string
{
    $fullPath = realpath($filePath);
    if ($fullPath === false) {
        throw new \Exception("File {$filePath} does not exist");
    }

    return $fullPath;
}

function getExtension(string $filePath): string
{
    $fullPath = getRealPath($filePath);
    return pathinfo($fullPath, PATHINFO_EXTENSION);
}

function normalizeDataValue(array $data): array
{
    return array_map(function ($value) {
        if ($value === true) {
            return 'true';
        } elseif ($value === false) {
            return 'false';
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_array($value)) {
            return normalizeDataValue($value);
        }

        return $value;
    }, $data);
}

function getData(string $filePath): array
{
    $fullPath = getRealPath($filePath);
    $data = file_get_contents($fullPath);
    $extension = getExtension($filePath);

    if ($data === false) {
        throw new \Exception("Can't read file");
    }

    return normalizeDataValue(parseData($data, $extension));
}

function getAstTree(array $data1, array $data2): array
{
    $firstDataKeys = array_keys($data1);
    $secondDataKeys = array_keys($data2);
    $keys = array_unique(array_merge($firstDataKeys, $secondDataKeys));
    $sortedKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    return array_map(
        function ($key) use ($data1, $data2) {
            $nestedValue1 = $data1[$key] ?? null;
            $nestedValue2 = $data2[$key] ?? null;

            if (is_array($nestedValue1) && is_array($nestedValue2)) {
                $nestedComparison = getAstTree($nestedValue1, $nestedValue2);

                return [
                    'key' => $key,
                    'type' => 'nested',
                    'value1' => $nestedComparison,
                    'value2' => $nestedComparison
                ];
            }

            if (!array_key_exists($key, $data2)) {
                return [
                    'key' => $key,
                    'type' => 'deleted',
                    'value1' => $nestedValue1,
                    'value2' => null
                ];
            }

            if (!array_key_exists($key, $data1)) {
                return [
                    'key' => $key,
                    'type' => 'added',
                    'value1' => null,
                    'value2' => $nestedValue2
                ];
            }

            if ($nestedValue1 !== $nestedValue2) {
                return [
                    'key' => $key,
                    'type' => 'changed',
                    'value1' => $nestedValue1,
                    'value2' => $nestedValue2,
                ];
            }

            return [
                'key' => $key,
                'type' => 'unchanged',
                'value1' => $nestedValue1,
                'value2' => $nestedValue2
            ];
        },
        $sortedKeys
    );
}

function genDiff(string $filePath1, string $filePath2, string $format = 'stylish'): string
{
    $data1 = getData($filePath1);
    $data2 = getData($filePath2);

    $diff = getAstTree($data1, $data2);
    $formattedDiff = getFormatter($diff, $format);

    return "{$formattedDiff}\n";
}
