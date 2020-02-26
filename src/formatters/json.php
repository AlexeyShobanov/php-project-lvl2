<?php

namespace Alshad\Gendiff\Formatters\Json;

function flattenAst ($ast)
{
//print_r("AST!!!!!!!!\n");
//print_r($ast);
    $flattenAst = function ($ast, $root) use (&$flattenAst) {
        //print_r("AST!!!!!!!!\n");
//print_r($ast);
        $result = array_reduce($ast, function($acc, $node) use (&$flattenAst, $root) {
        //print_r("NODE!!!!!!!!!!!!!\n");
        //print_r($node);
            if (array_key_exists('children', $node)) {
                $a = $flattenAst($node['children'], $node);
        print_r("A!!!!!!!!!!!!!\n");
        print_r($a);
                return array_merge($acc, $a);
            }
            if ($root) {
                //print_r("ROOT!!!!!!!!!!!!!\n");
                //print_r($root);
                //print_r("\n");
                $type = $root['type'] === 'unchanged' ? $node['type'] : $root['type'];
                return array_merge($acc, ['type' => $type, 'key' => $root['key'], 'value' => $node]);
                /*if ($root['type'] !== 'unchanged') {
                    $nodeWithParent = array_merge($root, ['value' => $node]);
                    return array_merge($acc, [$nodeWithParent]); 
                }
                $nodeWithParent = array_merge($root, ['value' => $node, 'type' => $node['type']]);
                return array_merge($acc, [$nodeWithParent]); */
            }
            return array_merge($acc, [$node]);
        }, []);

        return $result;
    };

    return $flattenAst($ast, '');
}

function renderAstForJsonFormat($ast)
{
    $flatAst = flattenAst($ast);
    $renderAst = function ($flatAst, $root) use (&$renderAst) {
        print_r($flatAst);
        print_r("\n");
        $jsonRepresentationOfNodes = array_reduce($flatAst, function ($acc, $node) use ($root, &$renderAst) {
            ['type' => $type, 'key' => $key, 'value' => $value] = $node;
            $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
            if (isset($node['oldValue'])) {
                $oldValue = $node['oldValue'];
                $modifiedOldValue = !is_bool($oldValue) ? $oldValue : ($oldValue === true ? 'true' : 'false');
            }
            return array_merge($acc, $type === 'changed' ?
                ["{$type}" => ["{$key}" => ["new" => "{$modifiedValue}", "old" => "{$modifiedOldValue}"]]] :
                ["{$type}" => ["{$key}" => "{$modifiedValue}"]]);
        },
            ['added' => [], 'removed' => [], 'changed' => [], 'unchanged' => []]
        );
        return $jsonRepresentationOfNodes;
    };

    $result = json_encode($renderAst($flatAst, ''), JSON_PRETTY_PRINT);

    return $result;
}
