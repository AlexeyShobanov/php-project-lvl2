<?php

namespace Alshad\Gendiff\Formatters\Json;

function renderAstForJsonFormat($ast) {
    $renderAst = function ($ast, $root) use (&$renderAst) {
        $jsonRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use ($root, &$renderAst) {
            ['type' => $type, 'key' => $key] = $node;
            if (array_key_exists('children', $node)) {
                return $acc;
            }
            if (array_key_exists('children', $node)) {

            }
        }, ['added' => [], 'removed' => [], 'changed' => [], 'unchanged' => []]);

    };
}