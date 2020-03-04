<?php

namespace Alshad\Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\generateDiff;

class DifferTest extends TestCase
{
    private const BEFORE_JSON_PATH = 'tests/fixtures/before.json';
    private const AFTER_JSON_PATH = 'tests/fixtures/after.json';
    private const BEFORE_YAML_PATH = 'tests/fixtures/before.yaml';
    private const AFTER_YAML_PATH = 'tests/fixtures/after.yaml';
    private const RESULT_JSON_PATH = 'tests/fixtures/result-in-json-format.txt';
    private const RESULT_PLAIN_PATH = 'tests/fixtures/result-in-plain-format.txt';
    private const RESULT_PRETTY_PATH = 'tests/fixtures/result-in-pretty-format.txt';

    private function getRealPath($path)
    {
        $modifyPath = __DIR__ . "/../{$path}";
        $absolutePath = realpath($modifyPath);
        return $absolutePath;
    }

    public function testGenerateDiffByJson()
    {
        $expected = file_get_contents($this->getRealPath(self::RESULT_PRETTY_PATH));
        $this->assertEquals($expected, generateDiff(self::BEFORE_JSON_PATH, self::AFTER_JSON_PATH));
    }

    public function testGenDiffByJsonForPlainFormat()
    {
        $expected = file_get_contents($this->getRealPath(self::RESULT_PLAIN_PATH));
        $this->assertEquals($expected, generateDiff(self::BEFORE_YAML_PATH, self::AFTER_YAML_PATH, 'plain'));
    }

    public function testGenDiffByJsonForJsonFormat()
    {
        $expected = file_get_contents($this->getRealPath(self::RESULT_JSON_PATH));
        $this->assertEquals($expected, generateDiff(self::BEFORE_JSON_PATH, self::AFTER_JSON_PATH, 'json'));
    }
}
