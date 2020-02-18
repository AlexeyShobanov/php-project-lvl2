<?php

namespace Alshad\Gendiff\Generate\Diff;

use function Alshad\Gendiff\Read\File\readFile;
use function Alshad\Gendiff\Parse\Data\parseData;
use function Alshad\Gendiff\Compare\Data\compareData;

function generateDiff($path1, $path2)
{
    $data1 = readFile($path1);
    $data2 = readFile($path2);
    print_r("\n");
    print_r($data1);
    print_r("\n");
    print_r($data2);
    print_r("\n");
    $parsedData1 = parseData($data1);
    $parsedData2 = parseData($data2);
    print_r("\n");
    var_dump($parsedData1);
    print_r("\n");
    var_dump($parsedData2);
    print_r("\n");
    $comparedList = compareData($parsedData1, $parsedData2);

    var_dump($comparedList);
    print_r("\n");

    return $comparedList;
}
