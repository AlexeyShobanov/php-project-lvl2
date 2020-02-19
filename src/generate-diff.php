<?php

namespace Alshad\Gendiff\Generate\Diff;

use function Alshad\Gendiff\Read\File\readFile;
use function Alshad\Gendiff\Parse\parseData;
use function Alshad\Gendiff\Compare\compareData;
use function Alshad\Gendiff\Render\renderAst;

function generateDiff($path1, $path2)
{
    $data1 = readFile($path1);
    $data2 = readFile($path2);
    $parsedData1 = parseData($data1);
    $parsedData2 = parseData($data2);
    $ast = compareData($parsedData1, $parsedData2);
    $result = renderAst($ast);
    print_r("\n");
    print_r($result);
    print_r("\n");

    return $result;
}
