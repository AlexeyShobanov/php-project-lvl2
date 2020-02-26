<?php

namespace Alshad\Gendiff\Formatters\Json;

define("INTENT", '  ');

function renderAstForJsonFormat($ast) {
    $renderAst = function ($ast, $root) use (&$renderAst) {
        //print_r($ast);
        //print_r("  555555555555555555555555555\n");
        $jsonRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use ($root, &$renderAst){
                ['type' => $type, 'key' => $key] = $node;
                if (array_key_exists('children', $node)) {

print_r($node['children']);
print_r("  555555555555555555555555555\n");
                    return array_merge($acc, ["{$type}" => $renderAst($node['children'], $node['key'])]);
                }

                $newValue = $root ? ["{$root}" => ["{$key}" => "{$node['value']}"]] : ["{$key}" => "{$node['value']}"];

                return array_merge($acc, ["{$type}" => $newValue]);

            },
            ['added' => [], 'removed' => [], 'changed' => [], 'unchanged' => []]
        );

        return $jsonRepresentationOfNodes;

    };
     
    $b = $renderAst($ast, '');
    $result = json_encode($b, JSON_PRETTY_PRINT);

    return $result;
}