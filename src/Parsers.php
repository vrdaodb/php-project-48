<?php

namespace Differ\Parsers;

function parseFile(string $filepath): object
{
    // Получаем абсолютный путь
    $absolutePath = realpath($filepath);

    if ($absolutePath === false || !file_exists($absolutePath)) {
        throw new \Exception("File not found: {$filepath}");
    }

    // Читаем содержимое файла
    $content = file_get_contents($absolutePath);

    if ($content === false) {
        throw new \Exception("Cannot read file: {$filepath}");
    }

    // Парсим JSON
    $data = json_decode($content);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("Invalid JSON in file: {$filepath}");
    }

    return $data;
}
