<?php

namespace Alshad\Gendiff\Parse\Data;

function parseData($data)
{
    $parsedData = json_decode($data, true);
    //print_r($parsedData);
    //print_r("\n");
    return $parsedData;
}
