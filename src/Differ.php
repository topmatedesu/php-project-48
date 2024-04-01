<?php

namespace Differ\Differ;

use function Differ\Parsers\fileDecode;

function genDiff(string $firstFilePath, string $secondFilePath): string
{

    $firstData = fileDecode($firstFilePath);
    $secondData = fileDecode($secondFilePath);

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
