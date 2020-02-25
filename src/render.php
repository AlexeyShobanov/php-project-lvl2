<?php

namespace Alshad\Gendiff\Render;

use function Alshad\Gendiff\Formatters\Pretty\renderAstForPrettyFormat;
use function Alshad\Gendiff\Formatters\Plain\renderAstForPlainFormat;


define("MAP_FOR_FORMATTERS", [
    'pretty' => 'Alshad\Gendiff\Formatters\Pretty\renderAstForPrettyFormat',
    'plain' => 'Alshad\Gendiff\Formatters\Plain\renderAstForPlainFormat'
]);

function renderAst($ast, $format)
{
    $result = MAP_FOR_FORMATTERS[$format]($ast);

    return $result;
}
