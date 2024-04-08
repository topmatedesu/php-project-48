<?php

namespace Differ\Formatters\Json;

function getJsonFormat(array $diffArray): string
{
    return json_encode($diffArray);
}
