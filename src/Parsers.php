<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseData($data, $formatData)
{
    $mapForParser = [
        'json' => function ($data) {
            return json_decode($data);
        },
        'yaml' => function ($data) {
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        },
        'yml' => function ($data) {
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        }
    ];

    $parsedData = $mapForParser[$formatData]($data);
    return $parsedData;
}
