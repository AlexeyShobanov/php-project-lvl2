<?php

namespace Gendiff\Formatters\Json;

function renderAstForJsonFormat($ast)
{
    $result = json_encode($ast, JSON_PRETTY_PRINT);
    return $result;
}
