<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\generateDiff;

class DifferTest extends TestCase
{
    private const BEFORE_JSON = 'before.json';
    private const AFTER_JSON = 'after.json';
    private const BEFORE_YAML = 'before.yaml';
    private const AFTER_YAML = 'after.yaml';
    private const RESULT_JSON = 'result-in-json-format.txt';
    private const RESULT_PLAIN = 'result-in-plain-format.txt';
    private const RESULT_PRETTY = 'result-in-pretty-format.txt';

    private function getRealPath($fileName)
    {
        $partsOfPath = [__DIR__, 'fixtures', $fileName];
        $modifyPath = implode(DIRECTORY_SEPARATOR, $partsOfPath);
        $absolutePath = realpath($modifyPath);
        return $absolutePath;
    }

    public function testPretty()
    {
        $expected = file_get_contents($this->getRealPath(self::RESULT_PRETTY));
        $beforeFilePath = $this->getRealPath(self::BEFORE_JSON);
        $afterFilePath = $this->getRealPath(self::AFTER_JSON);
        $this->assertEquals($expected, generateDiff($beforeFilePath, $afterFilePath));
    }

    public function testPlain()
    {
        $expected = file_get_contents($this->getRealPath(self::RESULT_PLAIN));
        $beforeFilePath = $this->getRealPath(self::BEFORE_YAML);
        $afterFilePath = $this->getRealPath(self::AFTER_YAML);
        $this->assertEquals($expected, generateDiff($beforeFilePath, $afterFilePath, 'plain'));
    }

    public function testJson()
    {
        $expected = file_get_contents($this->getRealPath(self::RESULT_JSON));
        $beforeFilePath = $this->getRealPath(self::BEFORE_JSON);
        $afterFilePath = $this->getRealPath(self::AFTER_JSON);
        $this->assertEquals($expected, generateDiff($beforeFilePath, $afterFilePath, 'json'));
    }
}
