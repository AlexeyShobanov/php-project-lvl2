<?php

namespace Alshad\Gendiff\Render;

function renderAst($ast)
{
    $mapTypeToSimbol = [
        'unchanged' => ' ',
        'added' => '+',
        'removed' => '-'
    ];
    $textRepresentationOfNodes = array_map(function ($node) use ($mapTypeToSimbol) {
        ['type' => $type, 'key' => $key, 'value' => $value] = $node;
        $symbolType = $mapTypeToSimbol[$type];
        $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
        return "  {$symbolType} {$key}: {$modifiedValue}";
    }, $ast);

    $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
    
    return "{\n{$textRepresentationOfAst}\n}";
}
