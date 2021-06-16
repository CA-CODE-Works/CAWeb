<?php

$finder = PhpCsFixer\Finder::create()->in(__DIR__);

return PhpCsFixer\Config::create()->setRules(array(
        'strict_comparison' => true,
        'no_mixed_echo_print' => [ 'use' => 'print' ],
        'yoda_style' => ['always_move_variable' => true]
    ))
    ->setRiskyAllowed(true)
    ->setIndent("\t")
    ->setLineEnding("\r\n")
    ->setFinder($finder)
    ->setUsingCache(false);
