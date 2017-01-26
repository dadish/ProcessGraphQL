ProcessGraphQL
==============

A GraphQL for ProcessWire.

At this point the module only loads the neccessary vendor code
to produce a little hello world example.

### Usage
To use the module you need to install this module in `site/modules`
directory. Then in one of your template files you do this.
```php
$modules->get('GraphQL')->execute();
```
This will handle the GraphQL queries for you. You checkout my
[GraphiQL][graphiql] module for ProcessWire for GraphQL explorer.

[graphiql]: https://github.com/dadish/pw-graphiql