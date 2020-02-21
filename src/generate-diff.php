<?php

namespace Alshad\Gendiff\Generate\Diff;

use function Alshad\Gendiff\Read\File\readFile;
use function Alshad\Gendiff\Parsers\parseData;
use function Alshad\Gendiff\Compare\makeAstForCompare;
use function Alshad\Gendiff\Render\renderAst;

function generateDiff($path1, $path2)
{
    $fullData1 = readFile($path1);
    $fullData2 = readFile($path2);
    $parsedData1 = parseData($fullData1['data'], $fullData1['formatData']);
    $parsedData2 = parseData($fullData2['data'], $fullData2['formatData']);
    $ast = makeAstForCompare($parsedData1, $parsedData2);
    print_r("\n");
    print_r($ast);
    print_r("\n");
    //
    //
    $result = renderAst($ast);
    //
    print_r("\n");
    //
    print_r($result);
    //
    print_r("\n");

    //return $result;
}
