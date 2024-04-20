<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stringifyTree;
use function Differ\Formatters\Plain\stringifyTreeToPlain;
use function Differ\Formatters\Json\getJsonFormat;

function format(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            return stringifyTree($diff);
        case 'plain':
            return stringifyTreeToPlain($diff);
        case 'json':
            return getJsonFormat($diff);
        default:
            throw new \Exception("Unknown format {$format}");
    }
}
