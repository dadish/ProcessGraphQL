<?php if (!$fullGraphiQL): ?>
  <style>
    .AdminThemeDefault #graphiql {
      margin-top: 30px;
    }
    #graphiql {
      height: 86vh;
      border: 5px solid #efefef;
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
  </style>
<?php endif; ?>
<div id="graphiql">Loading...</div>
<script>
  function graphQLFetcher(graphQLParams, options ) {
    const headers = options.headers || {};
    return fetch(
      window.config?.ProcessGraphQL?.GraphQLServerUrl || '',
      {
        method: 'post',
        headers: Object.assign({
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        }, headers),
        body: JSON.stringify(graphQLParams),
        credentials: 'include',
      },
    ).then(function (response) {
      return response.json().catch(function () {
        return response.text();
      });
    });
  }

  ReactDOM.render(
    React.createElement(GraphiQL, {
      fetcher: graphQLFetcher,
      defaultVariableEditorOpen: true,
    }),
    document.getElementById('graphiql'),
  );
</script>