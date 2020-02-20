<?php

namespace Alshad\Gendiff\Parse;

use Symfony\Component\Yaml\Yaml;

function parseData($data, $formatData)
{
    $mapForParser = [
        'json' => function ($data) {
            return json_decode($data, true);
        },
        'yaml' => function ($data) {
            return Yaml::parse($data);
        }
    ];

    $parsedData = $mapForParser[$formatData]($data);
    //print_r($parsedData);
    //print_r("\n");
    return $parsedData;
}
