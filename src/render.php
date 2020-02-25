<?php

namespace Alshad\Gendiff\Render;

define("MAP_TYPE_TO_SYMBOL", [
        'unchanged' => ' ',
        'added' => '+',
        'removed' => '-'
    ]);
define("BASE_INTENT", '  ');

function renderAst($ast)
{
    $renderAst = function ($ast, $root) use (&$renderAst) {
        $textRepresentationOfNodes = array_map(function ($node) use (&$renderAst, $root) {
            ['type' => $type, 'key' => $key] = $node;
            $typeAsSymbol = MAP_TYPE_TO_SYMBOL[$type];
            $root .= BASE_INTENT;
            if (array_key_exists('children', $node)) {
                $modifiedValue = $renderAst($node['children'], "{$root}" . BASE_INTENT);
            } else {
                $value = $node['value'];
                $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
            }
            return "{$root}{$typeAsSymbol} {$key}: {$modifiedValue}";
        }, $ast);
        $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);

        return "{\n{$textRepresentationOfAst}\n{$root}}";
    };

    return $renderAst($ast, '');
}
