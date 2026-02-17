<?php

namespace Differ\Builder;

use function Funct\Collection\sortBy;

function buildDiffTree(object $data1, object $data2): array
{
    $arr1 = get_object_vars($data1);
    $arr2 = get_object_vars($data2);

    $allKeys = array_unique(array_merge(array_keys($arr1), array_keys($arr2)));
    $sortedKeys = sortBy($allKeys, fn($key) => $key);

    return array_map(function ($key) use ($arr1, $arr2) {
        $hasKey1 = array_key_exists($key, $arr1);
        $hasKey2 = array_key_exists($key, $arr2);

        if (!$hasKey2) {
            return [
                'key' => $key,
                'type' => 'removed',
                'value' => $arr1[$key]
            ];
        }

        if (!$hasKey1) {
            return [
                'key' => $key,
                'type' => 'added',
                'value' => $arr2[$key]
            ];
        }

        $value1 = $arr1[$key];
        $value2 = $arr2[$key];

        // Если оба значения - объекты, делаем рекурсивное сравнение
        if (is_object($value1) && is_object($value2)) {
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => buildDiffTree($value1, $value2)
            ];
        }

        // Если значения равны
        if ($value1 === $value2) {
            return [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $value1
            ];
        }

        // Если значения разные
        return [
            'key' => $key,
            'type' => 'changed',
            'oldValue' => $value1,
            'newValue' => $value2
        ];
    }, $sortedKeys);
}
