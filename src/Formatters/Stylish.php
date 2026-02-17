<?php

namespace Differ\Formatters\Stylish;

function format(array $tree): string
{
    return formatTree($tree, 0);
}

function formatTree(array $tree, int $depth): string
{
    $indent = str_repeat(' ', $depth * 4);
    $bracketIndent = str_repeat(' ', $depth * 4);

    $lines = array_map(function ($node) use ($depth, $indent) {
        $key = $node['key'];
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                $children = formatTree($node['children'], $depth + 1);
                return "{$indent}    {$key}: {$children}";

            case 'unchanged':
                $value = formatValue($node['value'], $depth + 1);
                return "{$indent}    {$key}: {$value}";

            case 'removed':
                $value = formatValue($node['value'], $depth + 1);
                return "{$indent}  - {$key}: {$value}";

            case 'added':
                $value = formatValue($node['value'], $depth + 1);
                return "{$indent}  + {$key}: {$value}";

            case 'changed':
                $oldValue = formatValue($node['oldValue'], $depth + 1);
                $newValue = formatValue($node['newValue'], $depth + 1);
                return "{$indent}  - {$key}: {$oldValue}\n{$indent}  + {$key}: {$newValue}";

            default:
                throw new \Exception("Unknown node type: {$type}");
        }
    }, $tree);

    $result = implode("\n", $lines);
    return "{\n{$result}\n{$bracketIndent}}";
}

function formatValue($value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_object($value)) {
        return formatObject($value, $depth);
    }

    return (string) $value;
}

function formatObject(object $obj, int $depth): string
{
    $props = get_object_vars($obj);
    $indent = str_repeat(' ', $depth * 4);
    $bracketIndent = str_repeat(' ', ($depth - 1) * 4);

    if (empty($props)) {
        return '{}';
    }

    $lines = array_map(function ($key, $value) use ($indent, $depth) {
        $formattedValue = formatValue($value, $depth + 1);
        return "{$indent}    {$key}: {$formattedValue}";
    }, array_keys($props), array_values($props));

    $result = implode("\n", $lines);
    return "{\n{$result}\n{$bracketIndent}    }";
}
