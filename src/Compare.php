<?php

namespace Gendiff\Compare;

function makeAstForCompare($obj1, $obj2)
{
    $makeAstForCompare = function ($obj1, $obj2) use (&$makeAstForCompare) {
        
        $createNode = function ($type, $key, $value, $oldValue = null) use (&$makeAstForCompare) {
            return (is_object($value)) ? [
                'type' => $type,
                'key' => $key,
                'children' => $makeAstForCompare($value, $value)
                ] : [
                'type' => $type,
                'key' => $key,
                'value' => $value,
                'oldValue' => $oldValue
            ];
        };

        $data1 = (array)$obj1;
        $data2 = (array)$obj2;
        $mergedData = array_merge($data1, $data2);
        $ast = array_reduce(
            array_keys($mergedData),
            function ($acc, $key) use ($mergedData, $data1, $data2, &$makeAstForCompare, &$createNode) {
                if (!array_key_exists($key, $data1)) {
                    $acc[] = $createNode('added', $key, $mergedData[$key]);
                } elseif (!array_key_exists($key, $data2)) {
                    $acc[] = $createNode('removed', $key, $mergedData[$key]);
                } elseif (is_object($data1[$key]) && is_object($data2[$key])) {
                    $acc[] = [
                        'type' => 'unchanged',
                        'key' => $key,
                        'children' => $makeAstForCompare($data1[$key], $data2[$key])
                    ];
                } elseif ($data2[$key] === $data1[$key]) {
                    $acc[] = $createNode('unchanged', $key, $mergedData[$key]);
                } else {
                    $acc[] = $createNode('changed', $key, $mergedData[$key], $data1[$key]);
                }
                return $acc;
            },
            []
        );
        return $ast;
    };
    return $makeAstForCompare($obj1, $obj2);
}
