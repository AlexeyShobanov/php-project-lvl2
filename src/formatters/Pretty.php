<?php

namespace Gendiff\Formatters\Pretty;

define("MAP_TYPE_TO_SYMBOL", [
        'nested' => ' ',
        'unchanged' => ' ',
        'added' => '+',
        'removed' => '-'
    ]);
define("BASE_INDENT", '  ');

function makeTextRepresentationOfObj($obj, $indent)
{
    $data = (array)$obj;
    $keyValuePairs = array_map(function ($subKey) use ($data, $indent) {
        $keyValuePair = " {$subKey}: {$data[$subKey]}";
        return $indent . BASE_INDENT . ' ' . $keyValuePair;
    }, array_keys($data));
    $keyValuePairsText = implode("\n", $keyValuePairs);
    return "{\n{$keyValuePairsText}\n{$indent}}";
}

function stringify($node, $degreeOfNesting, &$renderAst, $selectedValue = 'value')
{
    ['type' => $type, 'key' => $key] = $node;
    $value = $node[$selectedValue];
    $indent = str_repeat(BASE_INDENT, $degreeOfNesting + 1);
    if ($type === 'nested') {
        $modifiedValue = $renderAst($node['children'], $degreeOfNesting + 1);
        return " {$key}: {$modifiedValue}";
    }
    if (is_object($value)) {
        $modifiedValue = makeTextRepresentationOfObj($value, $indent);
         return " {$key}: {$modifiedValue}";
    }
    $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
    return " {$key}: {$modifiedValue}";
}

function renderAstForPrettyFormat($ast)
{
    $renderAst = function ($ast, $degreeOfNesting) use (&$renderAst) {

        $textRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use (&$renderAst, $degreeOfNesting) {
            $type = $node['type'];
            $indent = str_repeat(BASE_INDENT, $degreeOfNesting + 1);
            $keyValuePairText = stringify($node, $degreeOfNesting + 1, $renderAst);
            switch ($type) {
                case 'changed':
                    $keyOldValuePairText = stringify($node, $degreeOfNesting + 1, $renderAst, 'oldValue');
                    return array_merge(
                        $acc,
                        [$indent . MAP_TYPE_TO_SYMBOL['added'] . $keyValuePairText],
                        [$indent . MAP_TYPE_TO_SYMBOL['removed'] . $keyOldValuePairText]
                    );
                case 'nested':
                case 'added':
                case 'removed':
                case 'unchanged':
                    return array_merge($acc, [$indent . MAP_TYPE_TO_SYMBOL[$type] . $keyValuePairText]);
                default:
                    throw new \Exception("Unknown node state: {$type}");
            }
            return $newAcc;
        }, []);
        $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
        $indent = str_repeat(BASE_INDENT, $degreeOfNesting);
        return "{\n{$textRepresentationOfAst}\n{$indent}}";
    };
    return $renderAst($ast, 0);
}
