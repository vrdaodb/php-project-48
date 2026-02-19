<?php

namespace Differ\Formatters;

function format(array $diffTree, string $format): string
{
    switch ($format) {
        case 'stylish':
            return \Differ\Formatters\Stylish\format($diffTree);
        case 'plain':
            return \Differ\Formatters\Plain\format($diffTree);
        case 'json':
            return \Differ\Formatters\Json\format($diffTree);
        default:
            throw new \Exception("Unknown format: {$format}");
    }
}
