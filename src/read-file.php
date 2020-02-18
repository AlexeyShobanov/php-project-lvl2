<?php

namespace Alshad\Gendiff\Read\File;

function readFile($path)
{
   
    set_include_path(getcwd());

    try {
        if (file_exists($path) && is_readable($path)) {
            $data = file_get_contents($path, FILE_USE_INCLUDE_PATH);
            //print_r($data);
            //print_r("\n");
            return $data;
        } else {
            throw new \Exception("{$path}");
        }
    } catch (\Exception $e) {
        echo "Can't open file: ", $e->getMessage(), "\n";
    }
}
