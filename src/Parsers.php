<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseData(string $data, string $extension): array
{
    switch ($extension) {
        case 'json':
            return json_decode($data, true);
        case 'yml':
            return Yaml::parse($data);
        case 'yaml':
            return Yaml::parse($data);
        default:
            throw new \Exception("Unknown extension {$extension}");
    }
}
