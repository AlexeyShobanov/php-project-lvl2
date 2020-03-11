<?php

namespace Gendiff\Render;

use function Gendiff\Formatters\Pretty\renderAstForPrettyFormat;
use function Gendiff\Formatters\Plain\renderAstForPlainFormat;
use function Gendiff\Formatters\Json\renderAstForJsonFormat;

const MAP_FOR_FORMATTERS = [
    'pretty' => 'Gendiff\Formatters\Pretty\renderAstForPrettyFormat',
    'plain' => 'Gendiff\Formatters\Plain\renderAstForPlainFormat',
    'json' => 'Gendiff\Formatters\Json\renderAstForJsonFormat'
];

function render($ast, $format)
{
    $result = MAP_FOR_FORMATTERS[$format]($ast);

    return $result;
}
