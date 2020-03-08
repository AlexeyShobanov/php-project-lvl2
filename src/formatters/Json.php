<?php

namespace Gendiff\Formatters\Json;

function renderAstForJsonFormat($ast)
{
    $result = json_encode($ast, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
    return $result;
}
