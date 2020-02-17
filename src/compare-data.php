<?php

namespace Alshad\Gendiff\Compare\Data;

function compareData($data1, $data2)
{
    $mergedData = array_merge($data1, $data2);
    $comparedList = array_reduce(array_keys($mergedData), function ($acc, $key) use ($mergedData, $data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            $acc[] = "  + {$key}: {$mergedData[$key]}";
        } elseif (!array_key_exists($key, $data2)) {
            $acc[] = "  - {$key}: {$mergedData[$key]}";
        } elseif ($data2[$key] === $data1[$key]) {
            $acc[] = "    {$key}: {$mergedData[$key]}";
        } else {
            $acc[] = "  + {$key}: {$data2[$key]}";
            $acc[] = "  - {$key}: {$data1[$key]}";
        }
        return $acc;
    }, []);

    $compareText = implode("\n", $comparedList);

    return "{\n{$compareText}\n}";
}
