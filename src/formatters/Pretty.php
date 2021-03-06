<?php

namespace Gendiff\Formatters\Pretty;

const BASE_INDENT = '  ';

function stringifyObj($obj, $indent)
{
    $data = (array)$obj;
    $keyValuePairs = array_map(function ($subKey) use ($data, $indent) {
        $keyValuePair = " {$subKey}: {$data[$subKey]}";
        return $indent . BASE_INDENT . ' ' . $keyValuePair;
    }, array_keys($data));
    $keyValuePairsText = implode("\n", $keyValuePairs);
    return "{\n{$keyValuePairsText}\n{$indent}}";
}

function stringify($value, $depth)
{
    $indent = str_repeat(BASE_INDENT, $depth + 1);
    if (is_object($value)) {
        return stringifyObj($value, $indent);
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function stringifyKeyValuePair($value, $key, $depth)
{
    $preparedValue = stringify($value, $depth + 1);
    return " {$key}: {$preparedValue}";
}

function renderAstForPrettyFormat($ast)
{
    $renderAstForPrettyFormat = function ($ast, $depth) use (&$renderAstForPrettyFormat) {
        $textRepresentationOfNodes = array_reduce(
            $ast,
            function ($acc, $node) use (&$renderAstForPrettyFormat, $depth) {
                ['type' => $type, 'key' => $key, 'value' => $value, 'oldValue' => $oldValue] = $node;
                $indent = str_repeat(BASE_INDENT, $depth + 1);
                switch ($type) {
                    case 'nested':
                        $modifiedValue = $renderAstForPrettyFormat($node['children'], $depth + 2);
                        $keyValuePairText = stringifyKeyValuePair($modifiedValue, $key, $depth);
                        return array_merge($acc, [$indent . ' ' . $keyValuePairText]);
                    case 'added':
                        $keyValuePairText = stringifyKeyValuePair($value, $key, $depth);
                        return array_merge($acc, [$indent . '+' . $keyValuePairText]);
                    case 'removed':
                        $keyValuePairText = stringifyKeyValuePair($value, $key, $depth);
                        return array_merge($acc, [$indent . '-' . $keyValuePairText]);
                    case 'unchanged':
                        $keyValuePairText = stringifyKeyValuePair($value, $key, $depth);
                        return array_merge($acc, [$indent . ' ' . $keyValuePairText]);
                    case 'changed':
                        $keyValuePairText = stringifyKeyValuePair($value, $key, $depth);
                        $keyOldValuePairText = stringifyKeyValuePair($oldValue, $key, $depth);
                        return array_merge(
                            $acc,
                            [$indent . '+' . $keyValuePairText],
                            [$indent . '-' . $keyOldValuePairText]
                        );
                    default:
                        throw new \Exception("Unknown node state: {$type}");
                }
            },
            []
        );
        $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
        $indent = str_repeat(BASE_INDENT, $depth);
        return "{\n{$textRepresentationOfAst}\n{$indent}}";
    };
    return $renderAstForPrettyFormat($ast, 0);
}
