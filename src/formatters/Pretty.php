<?php

namespace Gendiff\Formatters\Pretty;

define("MAP_TYPE_TO_SYMBOL", [
        'unchanged' => ' ',
        'added' => '+',
        'removed' => '-'
    ]);
define("BASE_INTENT", '  ');


function renderAstForPrettyFormat($ast)
{
    
    $getValue = function ($node, $intent) use (&$renderAst) {
        if ($type === 'nested') {
            return $renderAst($node['children'], "{$intent}" . BASE_INTENT);
        } elseif (is_object($value)) {
            $data = (array)$value;
            print_r($data);
        }
        return !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
    };


    $renderAst = function ($ast, $intent) use (&$renderAst, &$makeKeyValuePairText) {
        $textRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use (&$renderAst, &$makeKeyValuePairText, $intent) {
            ['type' => $type, 'key' => $key, 'value' => $value, 'oldValue' => $oldValue] = $node;
            $intent .= BASE_INTENT;
            $keyValuePairText = $makeKeyValuePairText($node);

            switch ($type) {
                    case 'changed':
                        $keyOldValuePairText = $makeKeyValuePairText($type, $key, $oldValue);
                        return array_merge(
                            $acc,
                            [$intent . MAP_TYPE_TO_SYMBOL['added'] . $keyValuePairText],
                            [$intent . MAP_TYPE_TO_SYMBOL['removed'] . $keyOldValuePairText]
                        );
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
