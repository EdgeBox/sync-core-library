<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src');

$config = new PhpCsFixer\Config();
return $config->setRules([
  '@Symfony' => true,
  'phpdoc_to_comment' => false,
])
    ->setFinder($finder);
