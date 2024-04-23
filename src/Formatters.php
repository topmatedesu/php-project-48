<?php

namespace Differ\Formatters;

function format(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            return Stylish\render($diff);
        case 'plain':
            return Plain\render($diff);
        case 'json':
            return Json\render($diff);
        default:
            throw new \Exception("Unknown format {$format}");
    }
}
