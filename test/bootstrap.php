<?php namespace ProcessWire\GraphQL;

require_once __DIR__ . "/../vendor/autoload.php";

use ProcessWire\ProcessWire;

$config = ProcessWire::buildConfig(__DIR__ . "/../vendor/processwire/processwire");

$wire = new ProcessWire($config);

echo \json_encode($config);