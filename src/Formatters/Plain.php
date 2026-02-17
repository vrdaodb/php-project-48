<?php

namespace Differ\Formatters\Plain;

function format(array $tree): string
{
    return formatTree($tree, '');
}

function formatTree(array $tree, string $path): string
{
    $lines = array_map(function ($node) use ($path) {
        $key = $node['key'];
        $currentPath = $path === '' ? $key : "{$path}.{$key}";
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                return formatTree($node['children'], $currentPath);

            case 'added':
                $value = formatValue($node['value']);
                return "Property '{$currentPath}' was added with value: {$value}";

            case 'removed':
                return "Property '{$currentPath}' was removed";

            case 'changed':
                $oldValue = formatValue($node['oldValue']);
                $newValue = formatValue($node['newValue']);
                return "Property '{$currentPath}' was updated. From {$oldValue} to {$newValue}";

            case 'unchanged':
                return null;

            default:
                throw new \Exception("Unknown node type: {$type}");
        }
    }, $tree);

    // Фильтруем null (unchanged) и объединяем
    $filtered = array_filter($lines, fn($line) => $line !== null);
    return implode("\n", $filtered);
}

function formatValue($value): string
{
    if (is_object($value) || is_array($value)) {
        return '[complex value]';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_int($value) || is_float($value)) {
        return (string) $value;
    }

    return "'{$value}'";
}
