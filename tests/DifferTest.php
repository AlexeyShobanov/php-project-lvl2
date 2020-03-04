<?php

namespace Alshad\Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\generateDiff;

class DifferTest extends TestCase
{
    public function testGenerateDiffByJson()
    {
        $result = file_get_contents('tests/fixtures/result-in-pretty-format.txt', FILE_USE_INCLUDE_PATH);
        $path1 = 'tests/fixtures/before.json';
        $path2 = 'tests/fixtures/after.json';
        $this->assertEquals($result, generateDiff($path1, $path2));
    }

    public function testGenerateDiffByYaml()
    {
        $result = file_get_contents('tests/fixtures/result-in-pretty-format.txt', FILE_USE_INCLUDE_PATH);
        $path1 = 'tests/fixtures/before.yaml';
        $path2 = 'tests/fixtures/after.yaml';
        $this->assertEquals($result, generateDiff($path1, $path2));
    }

    public function testGenDiffByJsonForPlainFormat()
    {
        $result = file_get_contents('tests/fixtures/result-in-plain-format.txt', FILE_USE_INCLUDE_PATH);
        $path1 = 'tests/fixtures/before.json';
        $path2 = 'tests/fixtures/after.json';
        $this->assertEquals($result, generateDiff($path1, $path2, 'plain'));
    }

    public function testGenDiffByJsonForJsonFormat()
    {
        $result = file_get_contents('tests/fixtures/result-in-json-format.txt', FILE_USE_INCLUDE_PATH);
        $path1 = 'tests/fixtures/before.json';
        $path2 = 'tests/fixtures/after.json';
        $this->assertEquals($result, generateDiff($path1, $path2, 'json'));
    }
}
