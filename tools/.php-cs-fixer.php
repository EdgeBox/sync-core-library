<?php

$finder = PhpCsFixer\Finder::create()
  ->in(__DIR__ . '/../src');

$config = new PhpCsFixer\Config();
return $config->setRules([
  '@Symfony' => true,
  '@PSR2' => true,
  '@PhpCsFixer' => true,
  'phpdoc_to_comment' => false,
  'phpdoc_align' => false,
  'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line']
])
  ->setFinder($finder);
