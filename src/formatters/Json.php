<?php

namespace Gendiff\Formatters\Json;

function renderAstForJsonFormat($ast)
{
    $mapForTypeNode = [
        'unchanged' => function ($node) {
            ['type' => $type, 'key' => $key, 'value' => $value] = $node;
            $modifiedValue = is_object($value) ? (array)$value : $value;
            return [$key =>
                        ['type' => $type, 'currentValue' => $modifiedValue, 'oldValue' => $modifiedValue]
                    ];
        },
                    
        'changed' => function ($node) {
            ['type' => $type, 'key' => $key, 'value' => $value, 'oldValue' => $oldValue] = $node;
            $modifiedValue = is_object($value) ? (array)$value : $value;
            $modifiedOldValue = is_object($oldValue) ? (array)$value : $oldValue;
            return [$key =>
                        ['type' => $type, 'currentValue' => $modifiedValue, 'oldValue' => $modifiedOldValue]
                    ];
        },
                    
        'added' => function ($node) {
            ['type' => $type, 'key' => $key, 'value' => $value] = $node;
            $modifiedValue = is_object($value) ? (array)$value : $value;
            return [$key =>
                        ['type' => $type, 'currentValue' => $modifiedValue, 'oldValue' => null]
                    ];
        },
                    
        'removed' => function ($node) {
            ['type' => $type, 'key' => $key, 'value' => $value] = $node;
            $modifiedValue = is_object($value) ? (array)$value : $value;
             return [$key =>
                        ['type' => $type, 'currentValue' => null, 'oldValue' => $modifiedValue]
                    ];
        }
    ];

    $renderAst = function ($ast) use (&$renderAst, $mapForTypeNode) {
        $jsonRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use (&$renderAst, $mapForTypeNode) {
            ['type' => $type, 'key' => $key] = $node;
            switch ($type) {
                case 'nested':
                    $keyValuePair = [$key => $renderAst($node['children'])];
                    return array_merge(
                        $acc,
                        [$key => $renderAst($node['children'])]
                    );
                case 'unchanged':
                case 'changed':
                case 'added':
                case 'removed':
                    return array_merge($acc, $mapForTypeNode[$type]($node));
                default:
                    throw new \Exception("Unknown node state: {$type}");
            }
        }, []);
        return $jsonRepresentationOfNodes;
    };

    $result = json_encode($renderAst($ast), JSON_PRETTY_PRINT);

    return $result;
}
