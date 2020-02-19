<?php

namespace Alshad\Gendiff\Compare;

function compareData($data1, $data2)
{
    $mergedData = array_merge($data1, $data2);
    $ast = array_reduce(array_keys($mergedData), function ($acc, $key) use ($mergedData, $data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            $acc[] = [
                'type' => 'added',
                'key' => $key,
                'value' => $mergedData[$key]
            ];
        } elseif (!array_key_exists($key, $data2)) {
            $acc[] = [
                'type' => 'removed',
                'key' => $key,
                'value' => $mergedData[$key]
            ];
        } elseif ($data2[$key] === $data1[$key]) {
            $acc[] = [
                'type' => 'unchanged',
                'key' => $key,
                'value' => $mergedData[$key]
            ];
        } else {
            $acc = array_merge($acc, [[
                    'type' => 'added',
                    'key' => $key,
                    'value' => $mergedData[$key]
                ], [
                    'type' => 'removed',
                    'key' => $key,
                    'value' => $data1[$key]]]
            );
        }
        return $acc;
    }, []);

    return $ast;
}
