<?php

namespace Alshad\Gendiff\Render;

define("MAP_TYPE_TO_SYMBOL", [
        'unchanged' => ' ',
        'added' => '+',
        'removed' => '-'
    ]);

function renderAst($ast)
{
    $renderAst = function ($ast) use (&$renderAst) {
        $textRepresentationOfNodes = array_map(function ($node) use (&$renderAst) {
            ['type' => $type, 'key' => $key] = $node;
            $typeAsSymbol = MAP_TYPE_TO_SYMBOL[$type];
            if (array_key_exists('children', $node)) {
                $textRepresentationOfChildrenNodes = $renderAst($node['children']);
                "  {$typeAsSymbol} {$key}: {$textRepresentationOfChildrenNodes}";
                return "  {$typeAsSymbol} {$key}: {$textRepresentationOfChildrenNodes}";
            } 
            $value = $node['value'];
            $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
            return "  {$typeAsSymbol} {$key}: {$modifiedValue}";
        }, $ast);

        $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
        return "{\n{$textRepresentationOfAst}\n}";
    };

    return $renderAst($ast);
}

/*function renderAst($ast)
{
    $renderAst = function ($ast) use (&$renderAst) {
        $textRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use (&$renderAst) {
            ['type' => $type, 'key' => $key] = $node;
            $typeAsSymbol = MAP_TYPE_TO_SYMBOL[$type];
            if (array_key_exists('children', $node)) {
                $textRepresentationOfChildrenNodes = $renderAst($node['children']);
                return "{$acc}\n  {$typeAsSymbol} {$key}: {$textRepresentationOfChildrenNodes}";
            } 
            $value = $node['value'];
            $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
            return "{$acc}\n  {$typeAsSymbol} {$key}: {$modifiedValue}";
        }, '');

        //$textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
        return "{{$textRepresentationOfNodes}\n}";
    };

    return $renderAst($ast);
}

*/


