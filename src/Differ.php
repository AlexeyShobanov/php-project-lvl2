<?php

namespace Gendiff\Differ;

use function Gendiff\Parsers\parseData;
use function Gendiff\Compare\makeAst;
use function Gendiff\Render\render;

function getRealPath($path)
{
    if (file_exists($path)) {
        return $path;
    }
    $partsOfPath = [getcwd(), $path];
    $modifyPath = implode(DIRECTORY_SEPARATOR, $partsOfPath);
    $absolutePath = realpath($modifyPath);
    return $absolutePath;
}

function readFile($path)
{
    $absolutePath = getRealPath($path);
    if (file_exists($absolutePath) && is_readable($absolutePath)) {
        $data = file_get_contents($absolutePath);
        return $data;
    } else {
        throw new \Exception("Can't open file: {$path}");
    }
}

function generateDiff($path1, $path2, $format = 'pretty')
{
    $data1 = readFile($path1);
    $data2 = readFile($path2);
    $extentionFile1 = pathinfo($path1, PATHINFO_EXTENSION);
    $extentionFile2 = pathinfo($path1, PATHINFO_EXTENSION);
    $parsedData1 = parseData($data1, $extentionFile1);
    $parsedData2 = parseData($data2, $extentionFile2);
    $ast = makeAst($parsedData1, $parsedData2);
    $result = render($ast, $format);
    return $result;
}
