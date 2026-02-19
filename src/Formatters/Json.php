<?php

namespace Differ\Formatters\Json;

function format(array $tree): string
{
    $result = json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if ($result === false) {
        throw new \Exception('JSON encoding failed: ' . json_last_error_msg());
    }

    return $result;
}
