<?php

// start the PHP session
ob_start();

$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");

// load dependencies
require_once $baseDir . "/vendor/autoload.php";

use ProcessWire\ProcessWire;

$wireConfig = ProcessWire::buildConfig($pwDir, null, [
"siteDir" => "site-default"
]);
$wire = new ProcessWire($wireConfig);
$modules = $wire->fuel('modules');

function cors() {

  // Allow from any origin
  if (isset($_SERVER['HTTP_ORIGIN'])) {
      // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
      // you want to allow, and if so:
      header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Max-Age: 86400');    // cache for 1 day
  }

  // Access-Control headers are received during OPTIONS requests
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
          // may also be using PUT, PATCH, HEAD etc
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
          header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

      exit(0);
    }
}

$module = $modules->get('ProcessGraphQL');
$module->legalTemplates = ['skyscrapers', 'skyscraper', 'architects', 'architect', 'cities', 'city'];
$module->legalFields = ['abbreviation', 'architects', 'body', 'born', 'email', 'featured', 'floors', 'freebase_guid', 'height', 'images', 'map', 'options', 'options_single', 'resume', 'selected', 'skyscrapers', 'sponsor', 'title', 'wikipedia_id', 'year'];


cors();
$res = $module->executeGraphQL();
echo json_encode($res);