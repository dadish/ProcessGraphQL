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

$scripts = scandir(realpath(__DIR__  . "/../GraphiQL/build/static/js"));
$scripts = array_walk($scripts, function ($filename) use ($config) {
  if (preg_match('/^main\..*\.js$/', $filename)) {
    $config->scripts->add(realpath(__DIR__ . "/../GraphiQL/build/static/js/{$filename}"));
  }
});

$styles = scandir(realpath(__DIR__  . "/../GraphiQL/build/static/css"));
$styles = array_walk($styles, function ($filename) use ($config) {
  if (preg_match('/^main\..*\.css$/', $filename)) {
    $config->styles->add(realpath(__DIR__ . "/../GraphiQL/build/static/css/{$filename}"));
  }
});

$config->js('ProcessGraphQL', [
  'GraphQLServerUrl' => 'http://localhost:8081',
]);

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico">
    <title>GraphiQL</title>
    <style>
      body {
        height: 100%;
        margin: 0;
        width: 100%;
        overflow: hidden;
      }
      #graphiql {
        height: 100vh;
      }
    </style>
  </head>
  <body>
    <style>
      <?php
        foreach($config->styles as $file) {
          require_once($file);
          echo "\n\n";
        }
      ?>
    </style>
    <div id="graphiql">Loading...</div>
    <script>
      var config = <?= json_encode($config->js()) ?>;
      <?php
        foreach($config->scripts as $file) {
          require_once($file);
          echo ";;\n";
        }
      ?>
    </script>
  </body>
</html>