<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\getData;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGetData(): void
    {
        $expected = [
            'host' => 'hexlet.io',
            'timeout' => 50,
            'proxy' => '123.234.53.22',
            'follow' => 'false',
            'bool' => 'true',
            'null' => 'null'
        ];

        $actual = '{
            "host": "hexlet.io",
            "timeout": 50,
            "proxy": "123.234.53.22",
            "follow": false,
            "bool": true,
            "null": null
        }';

        $this->assertEquals($expected, getData($actual));
    }

    public function testGenDiff(): void
    {
        $expected = file_get_contents('./tests/fixtures/diff');
        $actual = genDiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json');
        $this->assertEquals($expected, $actual);
    }
}
