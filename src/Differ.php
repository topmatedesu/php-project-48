<?php

namespace Differ\Differ;

function getData(string $data): mixed
{
    $dataDecode = json_decode($data, true);

    return array_map(function ($value) {
        if ($value === true) {
            return 'true';
        } elseif ($value === false) {
            return 'false';
        } elseif (is_null($value)) {
            return 'null';
        }

        return $value;
    }, $dataDecode);
}

function genDiff(string $firstFilePath, string $secondFilePath): string
{
    $firstContent = file_get_contents($firstFilePath);
    $secondContent = file_get_contents($secondFilePath);

    $firstData = getData($firstContent);
    $secondData = getData($secondContent);

    $firstDataKeys = array_keys($firstData);
    $secondDataKeys = array_keys($secondData);
    $keys = array_unique(array_merge($firstDataKeys, $secondDataKeys));
    sort($keys, SORT_REGULAR);

    $diff = array_reduce($keys, function ($acc, $key) use ($firstData, $secondData) {
        if (!array_key_exists($key, $firstData)) {
            $acc[] = '  + ' . $key . ': ' . $secondData[$key];
        } elseif (!array_key_exists($key, $secondData)) {
            $acc[] = '  - ' . $key . ': ' . $firstData[$key];
        } elseif ($firstData[$key] !== $secondData[$key]) {
            $acc[] = '  - ' . $key . ': ' . $firstData[$key];
            $acc[] = '  + ' . $key . ': ' . $secondData[$key];
        } else {
            $acc[] = '    ' . $key . ': ' . $firstData[$key];
        }

        return $acc;
    }, []);

    return '{' . "\n" . implode("\n", $diff) . "\n" . '}' . "\n";
}
