<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    private function getFixturePath(string $filename): string
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    public function testGenDiffNestedJson(): void
    {
        $file1 = $this->getFixturePath('nested-file1.json');
        $file2 = $this->getFixturePath('nested-file2.json');
        $expected = trim(file_get_contents($this->getFixturePath('nested-expected.txt')));
        
        $actual = genDiff($file1, $file2);
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiffNestedYaml(): void
    {
        $file1 = $this->getFixturePath('nested-file1.yml');
        $file2 = $this->getFixturePath('nested-file2.yml');
        $expected = trim(file_get_contents($this->getFixturePath('nested-expected.txt')));
        
        $actual = genDiff($file1, $file2);
        
        $this->assertEquals($expected, $actual);
    }
    
    public function testGenDiffJson(): void
{
    $file1 = $this->getFixturePath('nested-file1.json');
    $file2 = $this->getFixturePath('nested-file2.json');
    $expected = trim(file_get_contents($this->getFixturePath('json-expected.json')));

    $actual = genDiff($file1, $file2, 'json');

    $this->assertEquals($expected, $actual);
}
   public function testGenDiffPlain(): void
{
    $file1 = $this->getFixturePath('nested-file1.json');
    $file2 = $this->getFixturePath('nested-file2.json');
    $expected = trim(file_get_contents($this->getFixturePath('plain-expected.txt')));

    $actual = genDiff($file1, $file2, 'plain');

    $this->assertEquals($expected, $actual);
}
}
