<?php

namespace Gendiff\Compare;

use function Funct\Collection\union;

function buildNode($type, $key, $value, $oldValue = null, $children = null)
{
    return [
        'type' => $type,
        'key' => $key,
        'value' => $value,
        'oldValue' => $oldValue,
        'children' => $children
    ];
}

function makeAst($obj1, $obj2)
{
    $makeAst = function ($obj1, $obj2) use (&$makeAst) {
        $data1 = (array)$obj1;
        $data2 = (array)$obj2;
        $unionKeys = union(array_keys($data1), array_keys($data2));
        $ast = array_map(function ($key) use ($data1, $data2, &$makeAst) {
            if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
                if (is_object($data1[$key]) && is_object($data2[$key])) {
                    $children = $makeAst($data1[$key], $data2[$key]);
                    return buildNode('nested', $key, null, null, $children);
                } elseif ($data1[$key] === $data2[$key]) {
                    return buildNode('unchanged', $key, $data1[$key]);
                }
                return buildNode('changed', $key, $data2[$key], $data1[$key]);
            } elseif (array_key_exists($key, $data1)) {
                return buildNode('removed', $key, $data1[$key]);
            }
            return buildNode('added', $key, $data2[$key]);
        }, $unionKeys);

        return $ast;
    };
    return $makeAst($obj1, $obj2);
}
