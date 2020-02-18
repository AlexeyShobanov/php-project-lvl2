<?php

namespace Alshad\Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Alshad\Gendiff\Read\File\readFile;
use function Alshad\Gendiff\Generate\Diff\generateDiff;


class DiffTest extends TestCase
{
    public function testGenerateDiff()
    {
        $result = readFile('tests/fixtures/result.txt');
        $path1 = 'tests/fixtures/before.json';
        $path2 = 'tests/fixtures/after.json';
        $this->assertEquals($result, generateDiff($path1, $path2));
    }
}