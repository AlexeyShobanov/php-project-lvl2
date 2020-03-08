<?php

namespace Gendiff\Formatters\Json;

function renderAstForJsonFormat($ast)
{
    $result = json_encode($ast, JSON_FORCE_OBJECT);
    return $result;
}
