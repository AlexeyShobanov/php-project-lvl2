<?php

namespace Alshad\Gendiff\Formatters\Json;

use function Funct\Collection\flatten;

function flattenAst($ast)
{
    $flattenAst = function ($ast, $root) use (&$flattenAst) {
        $flatAst = array_reduce($ast, function ($acc, $node) use (&$flattenAst, $root) {
            if (array_key_exists('children', $node)) {
                ['type' => $type, 'key' => $key] = $node;
                $newRoot =   ['type' => $type, 'key' => $key];
                $flatAstChildren = $flattenAst($node['children'], $newRoot);
                $newNode = count($flatAstChildren) === 1 ? ($flatAstChildren)[0] : $flatAstChildren;
            } else {
                $newNode = $node;
            }

            if ($root) {
                $type = $root['type'] === 'unchanged' ? $newNode['type'] : $root['type'];
                $newNodeModify = ['type' => $type, 'key' => $root['key'], 'value' => $newNode];
            } else {
                $newNodeModify = $newNode;
            }

            $newAcc = array_key_exists('type', $newNodeModify) ?
            array_merge($acc, [$newNodeModify]) :
            array_merge($acc, $newNodeModify);
            return $newAcc;
        }, []);
        return $flatAst;
    };

    return ($flattenAst($ast, ''));
}

function flattenValueNode($node)
{
    $flattenValueNode = function ($node) use (&$flattenValueNode) {
        ['key' => $key, 'value' => $value] = $node;
        $modifiedValue = is_array($value) ?
        $flattenValueNode($value) :
        ($node['oldValue'] ? ["new" => $value, "old" => $node['oldValue']] : $value);
        $flatValue = [$key => $modifiedValue];
        return $flatValue;
    };

    return $flattenValueNode($node);
}

function renderAstForJsonFormat($ast)
{
    $flatAst = flattenAst($ast);
    $renderAst = function ($flatAst) use (&$renderAst) {
        $jsonRepresentationOfNodes = array_reduce(
            $flatAst,
            function ($acc, $node) use (&$renderAst) {
                $formattedNodeValue = flattenValueNode($node);
                ['type' => $type, 'key' => $key, 'value' => $value] = $node;
                if (array_key_exists($key, $acc[$type])) {
                    $acc[$type][$key] = array_merge($acc[$type][$key], $formattedNodeValue[$key]);
                } else {
                    $acc[$type] = array_merge($acc[$type], $formattedNodeValue);
                }
                return $acc;
            },
            ['added' => [], 'removed' => [], 'changed' => [], 'unchanged' => []]
        );
        return $jsonRepresentationOfNodes;
    };

    $result = json_encode($renderAst($flatAst, ''), JSON_PRETTY_PRINT);

    return $result;
}
