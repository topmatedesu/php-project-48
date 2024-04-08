<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $expected1 = file_get_contents('./tests/fixtures/stylish');

        $actual1 = genDiff('./tests/fixtures/file3.json', './tests/fixtures/file4.json');
        $this->assertEquals($expected1, $actual1);

        $actual2 = genDiff('./tests/fixtures/file3.yml', './tests/fixtures/file4.yml');
        $this->assertEquals($expected1, $actual2);

        $expected2 = file_get_contents('./tests/fixtures/plain');

        $actual3 = genDiff('./tests/fixtures/file3.json', './tests/fixtures/file4.json', 'plain');
        $this->assertEquals($expected2, $actual3);

        $actual4 = genDiff('./tests/fixtures/file3.yml', './tests/fixtures/file4.yml', 'plain');
        $this->assertEquals($expected2, $actual4);

        $expected3 = file_get_contents('./tests/fixtures/json');

        $actual5 = genDiff('./tests/fixtures/file3.json', './tests/fixtures/file4.json', 'json');
        $this->assertEquals($expected3, $actual5);

        $actual6 = genDiff('./tests/fixtures/file3.yml', './tests/fixtures/file4.yml', 'json');
        $this->assertEquals($expected3, $actual6);
    }
}
