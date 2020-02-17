<?php

namespace Alshad\Gendiff\Compare\Data;

function compareData($data1, $data2)
{
    $mergedData = array_merge($data1, $data2);
    $comparedList = array_reduce(array_keys($mergedData), function ($comparedList, $key) use ($mergedData, $data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            $comparedList[] = ['flag' => '+', 'key' => $key, 'value' => $mergedData[$key]];
        } elseif (!array_key_exists($key, $data2)) {
            $comparedList[] = ['flag' => '-', 'key' => $key, 'value' => $mergedData[$key]];
        } elseif ($date2($key) === $data1($key)) {
            $comparedList[] = ['flag' => ' ', 'key' => $key, 'value' => $mergedData[$key]];
        } else {
            $comparedList[] = [
                ['flag' => '+', 'key' => $key, 'value' => $data2[$key]],
                $comparedList[] = ['flag' => '-', 'key' => $key, 'value' => $data1[$key]]
            ];
        }
    }, []);

    return $comparedList;
}
