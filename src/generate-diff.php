<?php

namespace Alshad\Gendiff\Generate\Diff;

use function Alshad\Gendiff\Read\File\readFile;
use function Alshad\Gendiff\Parse\Data\parseData;
use function Alshad\Gendiff\Compare\Data\compareData;

function generateDiff($path1, $path2)
{
    $data1 = readFile($path1);
    $data2 = readFile($path2);
    $parsedData1 = parseData($data1);
    $parsedData2 = parseData($data2);
    $comparedList = compareData($parsedData1, $parsedData2);

    print_r($comparedList);
    print_r("\n");

    return $comparedList;
}
