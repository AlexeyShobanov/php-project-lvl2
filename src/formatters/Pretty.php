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

function stringify($value, $degreeOfNesting)
{
    $indent = str_repeat(BASE_INDENT, $degreeOfNesting + 1);
    if (is_object($value)) {
        return makeTextRepresentationOfObj($value, $indent);
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function renderAstForPrettyFormat($ast)
{
    $renderAstForPrettyFormat = function ($ast, $degreeOfNesting) use (&$renderAstForPrettyFormat) {
        $textRepresentationOfNodes = array_reduce(
            $ast,
            function ($acc, $node) use (&$renderAstForPrettyFormat, $degreeOfNesting) {
                ['type' => $type, 'key' => $key, 'value' => $value, 'oldValue' => $oldValue] = $node;
                $indent = str_repeat(BASE_INDENT, $degreeOfNesting + 1);
                switch ($type) {
                    case 'nested':
                        $modifiedValue = $renderAstForPrettyFormat($node['children'], $degreeOfNesting + 2);
                        $preparedValue = stringify($modifiedValue, $degreeOfNesting + 1);
                        $keyValuePairText = " {$key}: {$preparedValue}";
                        return array_merge($acc, [$indent . MAP_TYPE_TO_SYMBOL[$type] . $keyValuePairText]);
                    case 'added':
                    case 'removed':
                    case 'unchanged':
                        $preparedValue = stringify($value, $degreeOfNesting + 1);
                        $keyValuePairText = " {$key}: {$preparedValue}";
                        return array_merge($acc, [$indent . MAP_TYPE_TO_SYMBOL[$type] . $keyValuePairText]);
                    case 'changed':
                        $preparedValue = stringify($value, $degreeOfNesting + 1);
                        $keyValuePairText = " {$key}: {$preparedValue}";
                        $preparedOldValue = stringify($oldValue, $degreeOfNesting + 1);
                        $keyOldValuePairText = " {$key}: {$preparedOldValue}";
                        return array_merge(
                            $acc,
                            [$indent . MAP_TYPE_TO_SYMBOL['added'] . $keyValuePairText],
                            [$indent . MAP_TYPE_TO_SYMBOL['removed'] . $keyOldValuePairText]
                        );
                    default:
                        throw new \Exception("Unknown node state: {$type}");
                }
            },
            []
        );
        $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
        $indent = str_repeat(BASE_INDENT, $degreeOfNesting);
        return "{\n{$textRepresentationOfAst}\n{$indent}}";
    };
    return $renderAstForPrettyFormat($ast, 0);
}
