<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\renderStylish;
use function Differ\Formatters\Plain\renderPlain;
use function Differ\Formatters\Json\renderJson;

function format(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            return renderStylish($diff);
        case 'plain':
            return renderPlain($diff);
        case 'json':
            return renderJson($diff);
        default:
            throw new \Exception("Unknown format {$format}");
    }
}
