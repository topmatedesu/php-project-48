<?php

namespace Differ\Differ;

use function Differ\Parsers\fileDecode;
use function Differ\Formatters\getFormatter;

function genDiff(string $firstFilePath, string $secondFilePath, string $format = 'stylish'): string
{

    $firstData = fileDecode($firstFilePath);
    $secondData = fileDecode($secondFilePath);

    $diff = getFormatter($firstData, $secondData, $format);

    return "{$diff}\n";
}
