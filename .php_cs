<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__ . '/app')
    // ->in(__DIR__ . '/lib')
;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers(['-psr0'])
    ->finder($finder)
;

// vim: set filetype=php:
