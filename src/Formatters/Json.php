<?php

namespace Differ\Formatters\Json;

function renderJson(array $diff): string
{
    return json_encode($diff);
}
