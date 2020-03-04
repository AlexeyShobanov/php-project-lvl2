<?php

namespace Gendiff\Differ;

//use function Gendiff\Read\File\readFile;
use function Gendiff\Parsers\parseData;
use function Gendiff\Compare\makeAstForCompare;
use function Gendiff\Render\renderAst;

function readFile($path)
{
   
    set_include_path(getcwd());

    if (file_exists($path) && is_readable($path)) {
        $data = file_get_contents($path, FILE_USE_INCLUDE_PATH);
        $extention = pathinfo($path, PATHINFO_EXTENSION);
        return ['data' => $data, 'formatData' => $extention];
    } else {
        throw new \Exception("Can't open file: {$path}");
    }
}


function generateDiff($path1, $path2, $format = 'pretty')
{
    try {
        $fullData1 = readFile($path1);
        $fullData2 = readFile($path2);
        $parsedData1 = parseData($fullData1['data'], $fullData1['formatData']);
        $parsedData2 = parseData($fullData2['data'], $fullData2['formatData']);
        $ast = makeAstForCompare($parsedData1, $parsedData2);
        $result = renderAst($ast, $format);
        print_r("\n");
        print_r($result);
        print_r("\n");
        return $result;
    } catch (\Exception $e) {
        echo "Unknown order state: ", $e->getMessage(), "\n";
    }
}
