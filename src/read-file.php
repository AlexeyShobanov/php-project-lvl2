<?php

namespace Alshad\Gendiff\Read\File;

function readFile($path)
{
   
    set_include_path(getcwd());

    if (file_exists($path) && is_readable($path)) {
        $data = file_get_contents($path, FILE_USE_INCLUDE_PATH);
        $extention = pathinfo($path, PATHINFO_EXTENSION);
        return ['data' => $data, 'formatData' => $extention];
    } else {
        throw new \Exception("Can't open file: {$path}");
    }
}
