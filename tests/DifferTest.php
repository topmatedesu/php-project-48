<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private function getFixturePath(string $fixtureName): string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];

        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    public static function formatProvider(): array
    {
        return [
            ['file1.json', 'file2.json'],
            ['file1.yml', 'file2.yml']
        ];
    }

    #[DataProvider('formatProvider')]
    public function testDefaultOutput(string $file1, string $file2): void
    {
        $fixture1 = $this->getFixturePath($file1);
        $fixture2 = $this->getFixturePath($file2);
        $expectedDiff = $this->getFixturePath('stylish.txt');

        $this->assertStringEqualsFile($expectedDiff, genDiff($fixture1, $fixture2));
    }

    #[DataProvider('formatProvider')]
    public function testStylishFormat(string $file1, string $file2): void
    {
        $fixture1 = $this->getFixturePath($file1);
        $fixture2 = $this->getFixturePath($file2);
        $expectedDiff = $this->getFixturePath('stylish.txt');

        $this->assertStringEqualsFile($expectedDiff, genDiff($fixture1, $fixture2, 'stylish'));
    }

    #[DataProvider('formatProvider')]
    public function testPlainFormat(string $file1, string $file2): void
    {
        $fixture1 = $this->getFixturePath($file1);
        $fixture2 = $this->getFixturePath($file2);
        $expectedDiff = $this->getFixturePath('plain.txt');

        $this->assertStringEqualsFile($expectedDiff, genDiff($fixture1, $fixture2, 'plain'));
    }

    #[DataProvider('formatProvider')]
    public function testJsonFormat(string $file1, string $file2): void
    {
        $fixture1 = $this->getFixturePath($file1);
        $fixture2 = $this->getFixturePath($file2);
        $expectedDiff = $this->getFixturePath('json.txt');

        $this->assertStringEqualsFile($expectedDiff, genDiff($fixture1, $fixture2, 'json'));
    }
}
