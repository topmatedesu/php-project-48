<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Parsers\fileDecode;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testFileDecode(): void
    {
        $expected = [
            'host' => 'hexlet.io',
            'timeout' => 50,
            'proxy' => '123.234.53.22',
            'follow' => 'false'
        ];

        $actual1 = './tests/fixtures/file1.json';
        $this->assertEquals($expected, fileDecode($actual1));

        $actual2 = './tests/fixtures/file1.yml';
        $this->assertEquals($expected, fileDecode($actual2));
    }

    public function testGenDiff(): void
    {
        $expected = file_get_contents('./tests/fixtures/diff');

        $actual1 = genDiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json');
        $this->assertEquals($expected, $actual1);

        $actual2 = genDiff('./tests/fixtures/file1.yml', './tests/fixtures/file2.yml');
        $this->assertEquals($expected, $actual2);
    }
}
