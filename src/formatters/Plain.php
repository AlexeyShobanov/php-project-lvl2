<?php

namespace Gendiff\Formatters\Plain;

function renderAstForPlainFormat($ast)
{
    $renderAst = function ($ast, $root) use (&$renderAst) {
        $textRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use ($root, &$renderAst) {
            ['type' => $type, 'key' => $key, 'value' => $value, 'oldValue' => $oldValue] = $node;
            switch ($type) {
                case 'unchanged':
                    break;
                case 'nested':
                    $acc[] = $renderAst($node['children'], "{$root}{$key}.");
                    break;
                case 'removed':
                    $acc[] = "Property '{$root}{$key}' was removed";
                    break;
                case 'added':
                    $valueAsText = is_object($value) ? 'complex value' : $value;
                    $acc[] = "Property '{$root}{$key}' was added with value: '{$valueAsText}'";
                    break;
                case 'changed':
                    $acc[] = "Property '{$root}{$key}' was changed. From '{$oldValue}' to '{$value}'";
                    break;
                default:
                    throw new \Exception("Unknown node state: {$type}");
            }
            return $acc;
        }, []);
        return $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
    };
    return $renderAst($ast, '');
}
