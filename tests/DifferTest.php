<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private function getFixturePath(string $fixtureName): string
    {
        return __DIR__ . '/fixtures/' . $fixtureName;
    }

    public static function diffProvider(): array
    {
        return [
            ['file1.json', 'file2.json', 'stylish', 'stylish.txt'],
            ['file1.yml', 'file2.yml', 'stylish', 'stylish.txt'],
            ['file1.json', 'file2.json', 'plain', 'plain.txt'],
            ['file1.yml', 'file2.yml', 'plain', 'plain.txt'],
            ['file1.json', 'file2.json', 'json', 'json.txt'],
            ['file1.yml', 'file2.yml', 'json', 'json.txt']
        ];
    }

    #[DataProvider('diffProvider')]
    public function testGenDiff(string $file1, string $file2, string $format, string $expected): void
    {
        $fixture1 = $this->getFixturePath($file1);
        $fixture2 = $this->getFixturePath($file2);
        $expectedDiff = $this->getFixturePath($expected);

        $this->assertStringEqualsFile($expectedDiff, genDiff($fixture1, $fixture2, $format));
    }
}
