<?php

namespace Differ\Differ;

use function Funct\Collection\sortBy;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    // Парсим файлы
    $data1 = \Differ\Parsers\parseFile($pathToFile1);
    $data2 = \Differ\Parsers\parseFile($pathToFile2);

    // Преобразуем объекты в массивы
    $arr1 = get_object_vars($data1);
    $arr2 = get_object_vars($data2);

    // Получаем все уникальные ключи из обоих файлов
    $allKeys = array_unique(array_merge(array_keys($arr1), array_keys($arr2)));

    // Сортируем ключи по алфавиту
    $sortedKeys = sortBy($allKeys, fn($key) => $key);

    // Формируем строки диффа
    $lines = array_map(function ($key) use ($arr1, $arr2) {
        $hasKey1 = array_key_exists($key, $arr1);
        $hasKey2 = array_key_exists($key, $arr2);

        if ($hasKey1 && $hasKey2) {
            // Ключ есть в обоих файлах
            if ($arr1[$key] === $arr2[$key]) {
                // Значения совпадают
                return "    {$key}: " . formatValue($arr1[$key]);
            } else {
                // Значения различаются
                return "  - {$key}: " . formatValue($arr1[$key]) . "\n" .
                       "  + {$key}: " . formatValue($arr2[$key]);
            }
        } elseif ($hasKey1) {
            // Ключ только в первом файле
            return "  - {$key}: " . formatValue($arr1[$key]);
        } else {
            // Ключ только во втором файле
            return "  + {$key}: " . formatValue($arr2[$key]);
        }
    }, $sortedKeys);

    // Собираем результат
    return "{\n" . implode("\n", $lines) . "\n}";
}

function formatValue($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    return (string) $value;
}
