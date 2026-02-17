<?php

namespace Differ\Differ;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    // Парсим файлы
    $data1 = \Differ\Parsers\parseFile($pathToFile1);
    $data2 = \Differ\Parsers\parseFile($pathToFile2);

    // Строим дерево различий
    $diffTree = \Differ\Builder\buildDiffTree($data1, $data2);

    // Форматируем результат
    return formatDiff($diffTree, $format);
}

function formatDiff(array $diffTree, string $format): string
{
    switch ($format) {
        case 'stylish':
            return \Differ\Formatters\Stylish\format($diffTree);
        default:
            throw new \Exception("Unknown format: {$format}");
    }
}
