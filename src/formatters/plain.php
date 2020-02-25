<?php

namespace Alshad\Gendiff\Formatters\Plain;

define("MAP_TYPE_TO_PLAIN_TEXT", [
        'added' => '+',
        'removed' => '-'
    ]);

function renderAstForPlainFormat($ast)
{
    

    $renderAst = function ($ast, $root) use (&$renderAst) {
        $textRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use ($root, &$renderAst) {
 print_r("\n");
    print_r($node);
    print_r("\n");

            ['type' => $type, 'key' => $key] = $node;
            if ($type === 'unchanged' && !array_key_exists('children', $node)) {
                return $acc;
            }
            if (array_key_exists('children', $node)) {
                if ($type !== 'unchanged') {
                    $acc[] = "Property '{$root}{$key}' was added with value: 'complex value'";
                } else {
                    $acc[] = array_map(function ($childNode) use ($root, $key, &$renderAst) {
                        return $renderAst($childNode, "{$root}.{$key}");
                    }, $node['children']);
                }
                return $acc;
            }
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
                    //error
            }

            return $acc;
        }, []);
 print_r("\n");
    print_r($textRepresentationOfNodes);
    print_r("\n");

        return $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);
    };

    return $renderAst($ast, '');
}
