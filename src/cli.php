<?php

namespace Alshad\Gendiff\Cli;

use function Alshad\Gendiff\Generate\Diff\generateDiff;

function runCli()
{
    $doc = <<<DOC
    
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: pretty]
DOC;

    $args = \Docopt::handle($doc, array('version' => 'Generate diff version 0.0.1'));
    
    return generateDiff($args['<firstFile>'], $args['<secondFile>']);
}
