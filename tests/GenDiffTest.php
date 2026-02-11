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

    public function testGenDiff(): void
    {
        $file1 = $this->getFixturePath('file1.json');
        $file2 = $this->getFixturePath('file2.json');
        $expected = trim(file_get_contents($this->getFixturePath('expected.txt')));
        
        $actual = genDiff($file1, $file2);
        
        $this->assertEquals($expected, $actual);
    }
}
