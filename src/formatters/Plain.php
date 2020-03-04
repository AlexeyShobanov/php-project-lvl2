<?php

namespace Gendiff\Formatters\Plain;

function renderAstForPlainFormat($ast)
{
    $renderAst = function ($ast, $root) use (&$renderAst) {
        $textRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use ($root, &$renderAst) {
            ['type' => $type, 'key' => $key] = $node;
            if (array_key_exists('children', $node)) {
                switch ($type) {
                    case 'unchanged':
                        $acc[] = $renderAst($node['children'], "{$root}{$key}.");
                        break;
                    case 'removed':
                        $acc[] = "Property '{$root}{$key}' was removed";
                        break;
                    case 'added':
                        $acc[] = "Property '{$root}{$key}' was added with value: 'complex value'";
                        break;
                    default:
                        throw new \Exception("Unknown node state: {$type}");
                        break;
                }
            } elseif ($type !== 'unchanged') {
                ['value' => $value, 'oldValue' => $oldValue] = $node;
                switch ($type) {
                    case 'added':
                        $acc[] = "Property '{$root}{$key}' was added with value: '{$value}'";
                        break;
                    case 'removed':
                        $acc[] = "Property '{$root}{$key}' was removed";
                        break;
                    case 'changed':
                        $acc[] = "Property '{$root}{$key}' was changed. From '{$oldValue}' to '{$value}'";
                        break;
                    default:
                        throw new \Exception("Unknown node state: {$type}");
                        break;
                }
            }
            return $acc;
        }, []);
        return $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
    };

    return $renderAst($ast, '');
}
