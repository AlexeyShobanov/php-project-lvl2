<?php

namespace Alshad\Gendiff\Render;

use function Alshad\Gendiff\Formatters\Pretty\renderAstForPrettyFormat;
use function Alshad\Gendiff\Formatters\Plain\renderAstForPlainFormat;
use function Alshad\Gendiff\Formatters\Json\renderAstForJsonFormat;

define("MAP_FOR_FORMATTERS", [
    'pretty' => 'Alshad\Gendiff\Formatters\Pretty\renderAstForPrettyFormat',
    'plain' => 'Alshad\Gendiff\Formatters\Plain\renderAstForPlainFormat',
    'json' => 'Alshad\Gendiff\Formatters\Json\renderAstForJsonFormat'
]);

function renderAst($ast, $format)
{
    $result = MAP_FOR_FORMATTERS[$format]($ast);

    return $result;
}
