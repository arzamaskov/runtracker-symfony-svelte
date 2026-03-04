<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->notPath('Kernel.php')
    ->notPath('bootstrap.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        'declare_strict_types' => true,

        // Импорты
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_functions' => false,
            'import_constants' => false,
        ],

        // Чистота кода
        'no_empty_statement' => true,
        'no_superfluous_phpdoc_tags' => true,   // Убирает @param int $x если тип уже в сигнатуре
        'single_quote' => true,

        // Типизация (важно для DDD)
        'void_return' => true,                   // Явный :void
        'nullable_type_declaration' => true,      // ?Type вместо Type|null

        // Методы тестов в snake case
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
