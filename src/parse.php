<?php

namespace Alshad\Gendiff\Parse;

function parseData($data)
{
    $parsedData = json_decode($data, true);
    return $parsedData;
}
