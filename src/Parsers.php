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

function fileDecode(string $filePath): array
{
    $fullPath = getRealPath($filePath);
    $data = file_get_contents($fullPath);
    $extension = pathinfo($fullPath)['extension'];
    $dataArray = [];

    if ($extension === 'json') {
        $dataArray = json_decode($data, true);
    }

    if ($extension === 'yml' || $extension === 'yaml') {
        $dataArray = Yaml::parse($data);
    }

    return array_map(function ($value) {
        if ($value === true) {
            return 'true';
        } elseif ($value === false) {
            return 'false';
        } elseif (is_null($value)) {
            return 'null';
        }

        return $value;
    }, $dataArray);
}
