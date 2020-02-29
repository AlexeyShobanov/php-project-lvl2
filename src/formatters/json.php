<?php

namespace Alshad\Gendiff\Formatters\Json;

use function Funct\Collection\flatten;

function flattenAst ($ast)
{
    $flattenAst = function ($ast, $root) use (&$flattenAst) {
        if (!is_array($ast) || !count($ast)) {
            return null;
        }
print_r("AST\n");
print_r($ast);
print_r("\n");
        $result = array_reduce($ast, function($acc, $node) use (&$flattenAst, $root) {
//print_r("NODE\n");
//print_r($node);
//print_r("\n");
            /*['type' => $type, 'key' => $key] = $node;
            $newRoot =   ['type' => $type, 'key' => $key];
            $newNode = array_key_exists('children', $node) ?
            $flattenAst($node['children'], $newRoot) :
            $node;*/

if (array_key_exists('children', $node)) {
    ['type' => $type, 'key' => $key] = $node;
    $newRoot =   ['type' => $type, 'key' => $key];
    $a = $flattenAst($node['children'], $newRoot);
    print_r("AAAAAAAAAAAAAAAA\n");
print_r($a);
print_r("\n");
    $newNode = count($a) === 1 ? ($a)[0] : $a;
} else {
    $newNode = $node;
}

            //(!array_key_exists('type', $node) ? $node[0] : $node );
            if ($root) {
print_r("newNode\n");
print_r($newNode);
print_r("\n");
print_r("ROOT\n");
print_r($root);
print_r("\n");
                $type = $root['type'] === 'unchanged' ? $newNode['type'] : $root['type'];
//$type = $root['type'];
                $newNodeModify = ['type' => $type, 'key' => $root['key'], 'value' => $newNode];
            } else {
                $newNodeModify = $newNode;
            }
//print_r("newNodeModify\n");
//print_r($newNodeModify);
//print_r("\n");
            
            $acc[] = $newNodeModify;
print_r("ACC\n");
//print_r($acc);
print_r("\n");
            return $acc;
        }, []);
//print_r("RESULT\n");
//print_r($result);
//print_r("\n");
        return $result;
    };

    //return flatten($flattenAst($ast, ''));
    return ($flattenAst($ast, ''));
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
    $flatAst = flattenAst($ast);
    //$flatAst = $ast;
    $renderAst = function ($flatAst) use (&$renderAst) {
         print_r("FLATAST\n");
        print_r($flatAst);
        print_r("\n");
        $jsonRepresentationOfNodes = array_reduce($flatAst, function ($acc, $node) use (&$renderAst) {
            ['type' => $type, 'key' => $key, 'value' => $value] = $node;
            $a = flattenValueNode($node);
            //print_r("!!!!!!!!!!!!!!!!!!!!!!!!\n");
            //print_r($a);
        //print_r("\n");
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
