<?php if(!$fullGraphiQL): ?>
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
    /**
     * The below code is a copy from
     * https://github.com/graphql/graphiql/blob/master/packages/graphiql-examples/cdn/index.html
     * The only thing that's changed is the graphiql request url and added an X-Requested-With header.
     */
        // Parse the search string to get url parameters.
    var search = window.location.search;
    var parameters = {};
    search.substr(1).split('&').forEach(function (entry) {
        var eq = entry.indexOf('=');
        if (eq >= 0) {
            parameters[decodeURIComponent(entry.slice(0, eq))] =
                decodeURIComponent(entry.slice(eq + 1));
        }
    });

    // If variables was provided, try to format it.
    if (parameters.variables) {
        try {
            parameters.variables =
                JSON.stringify(JSON.parse(parameters.variables), null, 2);
        } catch (e) {
            // Do nothing, we want to display the invalid JSON as a string, rather
            // than present an error.
        }
    }

    // If headers was provided, try to format it.
    if (parameters.headers) {
        try {
            parameters.headers = JSON.stringify(
                JSON.parse(parameters.headers),
                null,
                2,
            );
        } catch (e) {
            // Do nothing, we want to display the invalid JSON as a string, rather
            // than present an error.
        }
    }
    // When the query and variables string is edited, update the URL bar so
    // that it can be easily shared.
    function onEditQuery(newQuery) {
        parameters.query = newQuery;
        updateURL();
    }
    function onEditVariables(newVariables) {
        parameters.variables = newVariables;
        updateURL();
    }
    function onEditHeaders(newHeaders) {
        parameters.headers = newHeaders;
        updateURL();
    }
    function onEditOperationName(newOperationName) {
        parameters.operationName = newOperationName;
        updateURL();
    }
    function updateURL() {
        var newSearch = '?' + Object.keys(parameters).filter(function (key) {
            return Boolean(parameters[key]);
        }).map(function (key) {
            return encodeURIComponent(key) + '=' +
                encodeURIComponent(parameters[key]);
        }).join('&');
        history.replaceState(null, null, newSearch);
    }
    // Defines a GraphQL fetcher using the fetch API. You're not required to
    // use fetch, and could instead implement graphQLFetcher however you like,
    // as long as it returns a Promise or Observable.
    function graphQLFetcher(graphQLParams, opts = { headers: {} }) {
        // When working locally, the example expects a GraphQL server at the path /graphql.
        // In a PR preview, it connects to the Star Wars API externally.
        // Change this to point wherever you host your GraphQL server.

        let headers = opts.headers;
        // Convert headers to an object.
        if (typeof headers === 'string') {
            headers = JSON.parse(opts.headers);
        }

        return fetch(config.ProcessGraphQL.GraphQLServerUrl, {
            method: 'post',
            headers: Object.assign({
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }, headers),
            body: JSON.stringify(graphQLParams),
            credentials: 'include',
        }).then(function (response) {
            return response.text();
        }).then(function (responseBody) {
            try {
                return JSON.parse(responseBody);
            } catch (error) {
                return responseBody;
            }
        });
    }
    // Render <GraphiQL /> into the body.
    // See the README in the top level of this module to learn more about
    // how you can customize GraphiQL by providing different values or
    // additional child elements.
    ReactDOM.render(
        React.createElement(GraphiQL, {
            fetcher: graphQLFetcher,
            query: parameters.query,
            variables: parameters.variables,
            headers: parameters.headers,
            operationName: parameters.operationName,
            onEditQuery: onEditQuery,
            onEditVariables: onEditVariables,
            onEditHeaders: onEditHeaders,
            defaultVariableEditorOpen: true,
            onEditOperationName: onEditOperationName,
            headerEditorEnabled: true,
            shouldPersistHeaders: true
        }),
        document.getElementById('graphiql')
    );
</script>
