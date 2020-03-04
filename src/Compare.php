<?php

namespace Gendiff\Compare;

use function Funct\Collection\union;

function makeAst($obj1, $obj2)
{
    $makeAst = function ($obj1, $obj2) use (&$makeAst) {
        $data1 = (array)$obj1;
        $data2 = (array)$obj2;
        $unionKeys = union(array_keys($data1), array_keys($data2));
        $ast = array_map(function ($key) use ($data1, $data2, &$makeAst) {
            if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
                if (is_object($data1[$key]) && is_object($data2[$key])) {
                    return [
                        'type' => 'unchanged',
                        'key' => $key,
                        'children' => $makeAst($data1[$key], $data2[$key])
                    ];
                } elseif ($data1[$key] === $data2[$key]) {
                    return [
                        'type' => 'unchanged',
                        'key' => $key,
                        'value' => $data1[$key],
                        'oldValue' => null
                    ];
                }
                return [
                        'type' => 'changed',
                        'key' => $key,
                        'value' => $data2[$key],
                        'oldValue' => $data1[$key]
                    ];
            } elseif (array_key_exists($key, $data1)) {
                if (is_object($data1[$key])) {
                    return [
                        'type' => 'removed',
                        'key' => $key,
                        'children' => $makeAst($data1[$key], $data1[$key])
                    ];
                }
                return [
                        'type' => 'removed',
                        'key' => $key,
                        'value' => $data1[$key],
                        'oldValue' => null
                    ];
            }
            if (is_object($data2[$key])) {
                return [
                        'type' => 'added',
                        'key' => $key,
                        'children' => $makeAst($data2[$key], $data2[$key])
                    ];
            }
            return [
                    'type' => 'added',
                    'key' => $key,
                    'value' => $data2[$key],
                    'oldValue' => null
                ];
        }, $unionKeys);

        return $ast;
    };
    return $makeAst($obj1, $obj2);
}
