<?php

namespace Gendiff\Formatters\Json;

function flattenAst($ast)
{
    $flattenAst = function ($ast, $root) use (&$flattenAst) {
        $flatAst = array_reduce($ast, function ($acc, $node) use (&$flattenAst, $root) {
            $type = $node['type'];
            switch ($type) {
                case 'nested':
                    $newNode = $flattenAst($node['children'], $node);
                    return array_merge($acc, $newNode);
                case 'unchanged':
                case 'removed':
                case 'added':
                case 'changed':
                    $newNode = $root ?
                    array_merge($root, ['type' => $type, 'value' => $node, 'children' => null]) :
                    $node;
                    $acc[] = $newNode;
                    return  $acc;
                default:
                    throw new \Exception("Unknown node state: {$type}");
            }
        }, []);
        return $flatAst;
    };

    return ($flattenAst($ast, ''));
}

function makeValueJsonNode($node)
{
    $makeValueJsonNode = function ($node) use (&$makeValueJsonNode) {
        ['type' => $type, 'key' => $key, 'value' => $value, 'oldValue' => $oldValue] = $node;
        $modifiedValue = is_array($value) ?
        $makeValueJsonNode($value) :
        ($node['oldValue'] ? ['newValue' => $value, 'oldValue' => $node['oldValue']] : $value);

        $valueJsonNode = [$key => $modifiedValue];
        return $valueJsonNode;
    };
    return $makeValueJsonNode($node);  
}

function renderAstForJsonFormat($ast)
{
    $flatAst = flattenAst($ast);
    $jsonNodes = array_reduce(
        $flatAst,
        function ($acc, $node) {
            $valueJsonNode = makeValueJsonNode($node);
            ['type' => $type, 'key' => $key] = $node;
            if (array_key_exists($key, $acc[$type])) {
                $acc[$type][$key] = array_merge($acc[$type][$key], $valueJsonNode[$key]);
            } else {
                $acc[$type] = array_merge($acc[$type], $valueJsonNode);
            }
            return $acc;
            },
        ['added' => [], 'removed' => [], 'changed' => [], 'unchanged' => []]
        );
    $result = json_encode($jsonNodes, JSON_PRETTY_PRINT);
    return $result;
}
