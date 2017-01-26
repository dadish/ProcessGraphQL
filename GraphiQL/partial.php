<style>
  #graphiql {
    height: 100vh;
  }
  #content .pw-container, #content .container{
    width: 100%;
    max-width: none;
  }
  <?= $CSS ?>
</style>
<div id="graphiql">Loading...</div>
<script>
  var GraphQLServerUrl = "<?= $GraphQLServerUrl ?>";
  <?= $JavaScript ?>
</script>