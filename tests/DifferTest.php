<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $expected = file_get_contents('./tests/fixtures/diff2');

        $actual1 = genDiff('./tests/fixtures/file3.json', './tests/fixtures/file4.json');
        $this->assertEquals($expected, $actual1);

        $actual2 = genDiff('./tests/fixtures/file3.yml', './tests/fixtures/file4.yml');
        $this->assertEquals($expected, $actual2);
    }
}
