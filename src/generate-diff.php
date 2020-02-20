<?php

namespace Alshad\Gendiff\Generate\Diff;

use function Alshad\Gendiff\Read\File\readFile;
use function Alshad\Gendiff\Parse\parseData;
use function Alshad\Gendiff\Compare\compareData;
use function Alshad\Gendiff\Render\renderAst;

function generateDiff($path1, $path2)
{
    $fullData1 = readFile($path1);
    $fullData2 = readFile($path2);
    $parsedData1 = parseData($fullData1['data'], $fullData1['formatData']);
    $parsedData2 = parseData($fullData2['data'], $fullData2['formatData']);
    $ast = compareData($parsedData1, $parsedData2);
    $result = renderAst($ast);
    print_r("\n");
    print_r($result);
    print_r("\n");

    return $result;
}
