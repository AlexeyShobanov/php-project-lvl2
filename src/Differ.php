<?php

namespace Gendiff\Differ;

use function Gendiff\Parsers\parseData;
use function Gendiff\Compare\makeAst;
use function Gendiff\Render\render;

function readFile($path)
{
    set_include_path(getcwd());
    if (file_exists($path) && is_readable($path)) {
        $data = file_get_contents($path, FILE_USE_INCLUDE_PATH);
        return $data;
    } else {
        throw new \Exception("Can't open file: {$path}");
    }
}

function generateDiff($path1, $path2, $format = 'pretty')
{
    try {
        $data1 = readFile($path1);
        $data2 = readFile($path2);
        $extentionFile1 = pathinfo($path1, PATHINFO_EXTENSION);
        $extentionFile2 = pathinfo($path1, PATHINFO_EXTENSION);
        $parsedData1 = parseData($data1, $extentionFile1);
        $parsedData2 = parseData($data2, $extentionFile2);
        $ast = makeAst($parsedData1, $parsedData2);
        $result = render($ast, $format);
        print_r('\n');
        print_r($result);
        print_r('\n');

        return $result;
    } catch (\Exception $e) {
        echo "Unknown order state: ", $e->getMessage(), "\n";
    }
}
