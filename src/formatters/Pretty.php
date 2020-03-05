<?php

namespace Gendiff\Formatters\Pretty;

define("MAP_TYPE_TO_SYMBOL", [
        'nested' => ' ',
        'unchanged' => ' ',
        'added' => '+',
        'removed' => '-'
    ]);
define("BASE_INTENT", '  ');

function makeTextRepresentationOfObj($obj, $intent)
{
    $data = (array)$obj;
    $keyValuePairs = array_map(function ($subKey) use ($data, $intent) {
        $keyValuePair = " {$subKey}: {$data[$subKey]}";
        return $intent . BASE_INTENT . ' ' . $keyValuePair;
    }, array_keys($data));
    $keyValuePairsText = implode("\n", $keyValuePairs);
    return "{\n{$keyValuePairsText}\n{$intent}}";
}

function makeKeyValuePairText($node, $intent, &$renderAst, $selectedValue = 'value')
{
    ['type' => $type, 'key' => $key] = $node;
    $value = $node[$selectedValue];
    if ($type === 'nested') {
        $modifiedValue = $renderAst($node['children'], $intent . BASE_INTENT);
    } elseif (is_object($value)) {
        $modifiedValue = makeTextRepresentationOfObj($value, $intent . BASE_INTENT);
    } else {
        $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
    }
    return " {$key}: {$modifiedValue}";
}

function renderAstForPrettyFormat($ast)
{
    $renderAst = function ($ast, $intent) use (&$renderAst) {
        $textRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use (&$renderAst, $intent) {
            $type = $node['type'];
            $intent .= BASE_INTENT;
            $keyValuePairText = makeKeyValuePairText($node, $intent, $renderAst);
            switch ($type) {
                case 'changed':
                    $keyOldValuePairText = makeKeyValuePairText($node, $intent, $renderAst, 'oldValue');
                    return array_merge(
                        $acc,
                        [$intent . MAP_TYPE_TO_SYMBOL['added'] . $keyValuePairText],
                        [$intent . MAP_TYPE_TO_SYMBOL['removed'] . $keyOldValuePairText]
                    );
                case 'nested':
                case 'added':
                case 'removed':
                case 'unchanged':
                    return array_merge($acc, [$intent . MAP_TYPE_TO_SYMBOL[$type] . $keyValuePairText]);
                default:
                    throw new \Exception("Unknown node state: {$type}");
            }
            return $newAcc;
        }, []);
        $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
        return "{\n{$textRepresentationOfAst}\n{$intent}}";
    };
    return $renderAst($ast, '');
}
