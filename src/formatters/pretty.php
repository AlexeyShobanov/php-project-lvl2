<?php

namespace Alshad\Gendiff\Formatters\Pretty;

define("MAP_TYPE_TO_SYMBOL", [
        'unchanged' => ' ',
        'added' => '+',
        'removed' => '-'
    ]);
define("BASE_INTENT", '  ');

function renderAstForPrettyFormat($ast)
{
    $renderAst = function ($ast, $intent) use (&$renderAst) {
        $textRepresentationOfNodes = array_reduce($ast, function ($acc, $node) use (&$renderAst, $intent) {
            ['type' => $type, 'key' => $key] = $node;
            $intent .= BASE_INTENT;
            if (array_key_exists('children', $node)) {
                $modifiedValue = $renderAst($node['children'], "{$intent}" . BASE_INTENT);
            } else {
                $value = $node['value'];
                $modifiedValue = !is_bool($value) ? $value : ($value === true ? 'true' : 'false');
                $oldValue = $node['oldValue'];
                $modifiedOldValue = !is_bool($oldValue) ? $oldValue : ($oldValue === true ? 'true' : 'false');
            }
            $newAcc = $type !== 'changed' ?
                array_merge($acc, [$intent . MAP_TYPE_TO_SYMBOL[$type] . " {$key}: {$modifiedValue}"]) :
                array_merge(
                    $acc,
                    [$intent . MAP_TYPE_TO_SYMBOL['added'] . " {$key}: {$modifiedValue}"],
                    [$intent . MAP_TYPE_TO_SYMBOL['removed'] . " {$key}: {$modifiedOldValue}"]
                );
            return $newAcc;
        }, []);

        $textRepresentationOfAst = implode("\n", $textRepresentationOfNodes);

        return "{\n{$textRepresentationOfAst}\n{$intent}}";
    };

    return $renderAst($ast, '');
}
