<?php

namespace Alshad\Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Alshad\Gendiff\Read\File\readFile;
use function Alshad\Gendiff\Generate\Diff\generateDiff;


class DiffTest extends TestCase
{
    public function testGenerateDiffByFlatJson()
    {
        $result = readFile('tests/fixtures/result-flat.txt')['data'];
        $path1 = 'tests/fixtures/before-flat.json';
        $path2 = 'tests/fixtures/after-flat.json';
        //print_r(generateDiff($path1, $path2));
        $this->assertEquals($result, generateDiff($path1, $path2));
    }

    public function testGenerateDiffByFlatYaml()
    {
        $result = readFile('tests/fixtures/result-flat.txt')['data'];
        $path1 = 'tests/fixtures/before-flat.yaml';
        $path2 = 'tests/fixtures/after-flat.yaml';
        //print_r(generateDiff($path1, $path2));
        $this->assertEquals($result, generateDiff($path1, $path2));
    }

    public function testGenerateDiffByJson()
    {
        $result = readFile('tests/fixtures/result.txt')['data'];
        $path1 = 'tests/fixtures/before.json';
        $path2 = 'tests/fixtures/after.json';
        $this->assertEquals($result, generateDiff($path1, $path2));
    }

    public function testGenerateDiffByYaml()
    {
        $result = readFile('tests/fixtures/result.txt')['data'];
        $path1 = 'tests/fixtures/before.yaml';
        $path2 = 'tests/fixtures/after.yaml';
        $this->assertEquals($result, generateDiff($path1, $path2));
    }

    public function testGenDiffByJsonForPlainFormat()
    {
        $result = readFile('tests/fixtures/result-plain.txt')['data'];
        $path1 = 'tests/fixtures/before.json';
        $path2 = 'tests/fixtures/after.json';
        $this->assertEquals($result, generateDiff($path1, $path2, 'plain'));
    }

    public function testGeneDiffByYamlForPlainFormat()
    {
        $result = readFile('tests/fixtures/result-plain.txt')['data'];
        $path1 = 'tests/fixtures/before.yaml';
        $path2 = 'tests/fixtures/after.yaml';
        $this->assertEquals($result, generateDiff($path1, $path2, 'plain'));
    }

    public function testGenDiffByJsonForJsonFormat()
    {
        $result = readFile('tests/fixtures/result-json.txt')['data'];
        $path1 = 'tests/fixtures/before.json';
        $path2 = 'tests/fixtures/after.json';
        $this->assertEquals($result, generateDiff($path1, $path2, 'json'));
    }

    public function testGeneDiffByYamlForJsonFormat()
    {
        $result = readFile('tests/fixtures/result-json.txt')['data'];
        $path1 = 'tests/fixtures/before.yaml';
        $path2 = 'tests/fixtures/after.yaml';
        $this->assertEquals($result, generateDiff($path1, $path2, 'json'));
    }
}