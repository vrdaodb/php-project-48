<?php

namespace Differ\Differ;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $data1 = \Differ\Parsers\parseFile($pathToFile1);
    $data2 = \Differ\Parsers\parseFile($pathToFile2);

    $diffTree = \Differ\Builder\buildDiffTree($data1, $data2);

    return \Differ\Formatters\format($diffTree, $format);
}
