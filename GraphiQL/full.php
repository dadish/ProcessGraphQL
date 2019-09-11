<!DOCTYPE html>
<html>
  <head>
    <?php foreach($config->styles as $file) echo "\n\t<link type='text/css' href='$file' rel='stylesheet' />"; ?>
    <style>
      body {
        height: 100%;
        margin: 0;
        width: 100%;
        overflow: hidden;
      }
      #graphiql {
        height: 100vh;
        width: 100%;
      }
    </style>

    <?php foreach($config->scripts as $file) echo "\n\t<script type='text/javascript' src='$file'></script>"; ?>

  </head>
  <body>
    <script>
      // set the processwire config variables
      var config = <?= json_encode($config->js()) ?>;
    </script>
    <?php
      $fullGraphiQL = true;
      require_once(realpath(__DIR__ . "/partial.php"));
    ?>
  </body>
</html>