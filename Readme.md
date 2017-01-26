ProcessGraphQL
==============

A GraphQL for ProcessWire.

The module seamlessly integrates to your [ProcessWire][pw] web app and allowa you to
serve the GraphQL api of your existing app. You don't need to apply changes to
your content or it's structure. 

Here is an example of ProcessGraphQL in action after installing it to 
[skyscrapers][pw-skyscrapers] profile.

![ProcessGraphQL Simple Query][img-query]

ProcessGraphQL supports filtering via [ProcessWire Selectors][pw-selectors].

![ProcessGraphQL Supports ProcessWire Selectors][img-filtering]

ProcessGraphQL supports complex fields like FieldtypeImage or FieldtypePage.

![ProcessGraphQL Supporting FieldtypeImage and FieldtypePage][img-fieldtypes]

### Installation
To install module place the contents of this directory into your `/site/modules/`
directory and go to `Setup -> Modules` in your ProcessWire admin panel and click
__Refresh__ button. You should see the ProcessGraphQL module that you can install
by clicking the __Install__ button next to it.

After you installed the ProcessGraphQL, you can go to `Setup -> GraphQL` in your
admin panel and you will see the GraphiQL where you can perform queries to your
GraphQL api.
directory. 
```php
$modules->get('GraphQL')->execute();
```
This will handle the GraphQL queries for you. You checkout my
[GraphiQL][graphiql] module for ProcessWire for GraphQL explorer.

[graphql]: http://graphql.org/
[graphiql]: https://github.com/graphql/graphiql/
[pw]: https://processwire.com
[pw-skyscrapers]: http://demo.processwire.com/
[pw-selectors]: https://processwire.com/api/selectors/
[img-query]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Query.gif
[img-filtering]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Filtering.gif
[img-fieldtypes]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Fieldtypes.gif
