<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getRealPath(string $filePath): string
{
    $fullPath = realpath($filePath);
    if ($fullPath === false) {
        throw new \Exception("File {$filePath} does not exist");
    }

    return $fullPath;
}

function getFormattedArray(array $dataArray): array
{
    return array_map(function ($value) {
        if ($value === true) {
            return 'true';
        } elseif ($value === false) {
            return 'false';
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_array($value)) {
            return getFormattedArray($value);
        }

        return $value;
    }, $dataArray);
}

function fileDecode(string $filePath): mixed
{
    $fullPath = getRealPath($filePath);
    $data = file_get_contents($fullPath);
    $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

    if ($data === false) {
        throw new \Exception("Can't read file");
    }

    switch ($extension) {
        case 'json':
            return getFormattedArray(json_decode($data, true));
        case 'yml':
            return getFormattedArray(Yaml::parse($data));
        case 'yaml':
            return getFormattedArray(Yaml::parse($data));
        default:
            throw new \Exception("Unknown extension {$extension}");
    }
}
