<?php

declare(strict_types=1);


$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PER-CS' => true,      // The latest PHP styling standard
    '@PSR12' => true,       // Legacy standard, still very common
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
])
->setFinder($finder);
