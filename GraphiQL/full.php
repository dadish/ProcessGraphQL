<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico">
    
    <?php foreach($config->styles as $file) echo "\n\t<link type='text/css' href='$file' rel='stylesheet' />"; ?>
    <?php foreach($config->scripts as $file) echo "\n\t<script type='text/javascript' src='$file'></script>"; ?>
    <title>GraphiQL</title>
    <style>
      body {
        height: 100%;
        margin: 0;
        width: 100%;
        overflow: hidden;
      }
    </style>
    <script>
      var config = <?= json_encode($config->js()) ?>;
    </script>
  </head>
  <body>
    <style>
      #graphiql {
        height: 100vh;
      }
    </style>
    <div id="graphiql">Loading...</div>
  </body>
</html>