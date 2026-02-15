<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

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

    // Определяем формат по расширению
    $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);

    return parse($content, $extension);
}

function parse(string $content, string $format): object
{
    switch ($format) {
        case 'json':
            return parseJson($content);
        case 'yml':
        case 'yaml':
            return parseYaml($content);
        default:
            throw new \Exception("Unsupported format: {$format}");
    }
}

function parseJson(string $content): object
{
    $data = json_decode($content);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("Invalid JSON: " . json_last_error_msg());
    }

    return $data;
}

function parseYaml(string $content): object
{
    $data = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);

    if (!is_object($data)) {
        throw new \Exception("Invalid YAML");
    }

    return $data;
}
