<?php

namespace Gendiff\Formatters\Json;

function renderAstForJsonFormat($ast)
{
    $renderAst = function ($ast) use (&$renderAst) {
        $jsonRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use (&$renderAst) {
            ['type' => $type, 'key' => $key, 'value' => $value, 'oldValue' => $oldValue] = $node;
            $modifiedValue = is_object($value) ? (array)$value : $value;
            $modifiedOldValue = is_object($oldValue) ? (array)$value : $oldValue;
            switch ($type) {
                case 'nested':
                    $keyValuePair = [$key => $renderAst($node['children'])];
                    return array_merge(
                        $acc,
                        [$key => $renderAst($node['children'])]
                    );
                case 'unchanged':
                    return array_merge($acc, [$key =>
                        ['type' => $type, 'currentValue' => $modifiedValue, 'oldValue' => $modifiedValue]
                    ]);
                case 'changed':
                    return array_merge($acc, [$key =>
                        ['type' => $type, 'currentValue' => $modifiedValue, 'oldValue' => $modifiedOldValue]
                    ]);
                case 'added':
                    return array_merge($acc, [$key =>
                        ['type' => $type, 'currentValue' => $modifiedValue, 'oldValue' => null]
                    ]);
                case 'removed':
                    return array_merge($acc, [$key =>
                        ['type' => $type, 'currentValue' => null, 'oldValue' => $modifiedValue]
                    ]);
                default:
                    throw new \Exception("Unknown node state: {$type}");
            }
        }, []);
        return $jsonRepresentationOfNodes;
    };

    $result = json_encode($renderAst($ast), JSON_PRETTY_PRINT);

    return $result;
}
