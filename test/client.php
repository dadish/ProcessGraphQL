<?php

$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");

// load dependencies
require_once $baseDir . "/vendor/autoload.php";

use ProcessWire\ProcessWire;

$wireConfig = ProcessWire::buildConfig($pwDir, null, [
"siteDir" => "site-default"
]);
$wire = new ProcessWire($wireConfig);

// =====================
// Setup GraphiQL Assets
// =====================
$config = $wire->fuel('config');

$config->js('ProcessGraphQL', [
  'GraphQLServerUrl' => 'http://localhost:8081',
]);

$config->scripts->add("https://cdn.jsdelivr.net/es6-promise/4.0.5/es6-promise.auto.min.js");
$config->scripts->add("https://cdn.jsdelivr.net/fetch/0.9.0/fetch.min.js");
$config->scripts->add("https://cdn.jsdelivr.net/react/15.4.2/react.min.js");
$config->scripts->add("https://cdn.jsdelivr.net/react/15.4.2/react-dom.min.js");
$config->scripts->add('node_modules/graphiql/graphiql.min.js');
$config->styles->add('node_modules/graphiql/graphiql.css');

$filename = realpath($baseDir . $_SERVER['SCRIPT_NAME']);
if ($filename === $baseDir) {
  require_once(realpath("$baseDir/GraphiQL/full.php"));
  return;
}

if (!file_exists($filename)) {
  http_response_code(404);
  echo "Not Found!";
  return;
}

if (preg_match('/^[a-zA-Z\/_.]+\.js$/', $filename)) {
  header('Content-type: text/javascript');
} else if (preg_match('/^[a-zA-Z\/_.]+\.css$/', $filename)) {
  header('Content-type: text/css');
}
require_once($filename);