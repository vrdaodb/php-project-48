<?php

namespace Differ\Formatters\Json;

function format(array $tree): string
{
    return json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
