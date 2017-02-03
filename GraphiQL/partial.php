<style>
  #content.pw-content{
    padding: 0px;
  }
  
  <?php if($fullWidth): ?>
  #content .container.pw-container{
    max-width: none;
    margin: 0px;
    width: 100%;
  }
  <?php endif; ?>

  #graphiql {
    height: 80vh;
    border-right: 1px solid #efefef;
  }
  #graphiql * {
    box-sizing: content-box;
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    line-height: 1rem;
  }
  #graphiql .doc-explorer-title{
    overflow: hidden;
  }
  #graphiql .graphiql-container .doc-explorer-back{
    line-height: 0.85rem;
  }
  #graphiql .doc-explorer-contents{
    padding: 5px 20px 15px;
  }
  #graphiql .graphiql-container .search-box{
    margin: 5px 0px 8px 0;
  }
  #graphiql .graphiql-container .search-box:before{
    top: 5px;
  }
  #graphiql .graphiql-container .search-box input{
    padding: 6px 0px 8px 24px;
    width: 94%;
    background: none;
  }
  <?= $CSS ?>
</style>
<div id="graphiql">Loading...</div>
<script>
  var GraphQLServerUrl = "<?= $GraphQLServerUrl ?>";
  <?= $JavaScript ?>
</script>