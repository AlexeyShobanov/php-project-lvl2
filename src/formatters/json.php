<?php

namespace Alshad\Gendiff\Formatters\Json;

function flattenAst ($ast)
{
    $flattenAst = function ($ast, $root) use (&$flattenAst) {
        $result = array_reduce($ast, function($acc, $node) use (&$flattenAst, $root) {
            if (array_key_exists('children', $node)) {
                $flatChildrenAst = $flattenAst($node['children'], $node);
                return array_merge($acc, $flatChildrenAst);
            }
            if ($root) {
                $type = $root['type'] === 'unchanged' ? $node['type'] : $root['type'];
                $acc[] = ['type' => $type, 'key' => $root['key'], 'value' => $node];
            } else {
                $acc[] = $node;
            }
            return $acc;
        }, []);

        return $result;
    };

    return $flattenAst($ast, '');
}

function flattenValueNode ($node)
{
    $flattenValueNode = function ($node) use (&$flattenValueNode) {
        ['key' => $key, 'value' => $value] = $node;
        if (is_array($value)) {
            $b = $flattenValueNode($value);
                $modifiedValue = $b;
        } else {
            $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
            if ($node['oldValue']) {
                $oldValue = $node['oldValue'];
                $modifiedOldValue = !is_bool($oldValue) ? $oldValue : ($oldValue === true ? 'true' : 'false');
                $modifiedValue = ["new" => $modifiedValue, "old" => $modifiedOldValue];
            }
        }
        $flatValue = [$key => $modifiedValue];

        return $flatValue;
    };

    return $flattenValueNode($node);
}

function renderAstForJsonFormat($ast)
{
    //$flatAst = flattenAst($ast);
    $flatAst = $ast;
    $renderAst = function ($flatAst) use (&$renderAst) {
        print_r($flatAst);
        print_r("\n");
        $jsonRepresentationOfNodes = array_reduce($flatAst, function ($acc, $node) use (&$renderAst) {
            ['type' => $type, 'key' => $key, 'value' => $value] = $node;
            $a = flattenValueNode($node);
            print_r("!!!!!!!!!!!!!!!!!!!!!!!!\n");
            print_r($a);
        print_r("\n");
        $acc[$type][] = $a;
        //$b = array_merge($acc, [$type => $a]);
        //print_r("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n");
          //  print_r($acc);
        //print_r("\n");
            return $acc;
        },
            ['added' => [], 'removed' => [], 'changed' => [], 'unchanged' => []]
        );
        return $jsonRepresentationOfNodes;
    };

    $result = json_encode($renderAst($flatAst, ''), JSON_PRETTY_PRINT);

    return $result;
}
