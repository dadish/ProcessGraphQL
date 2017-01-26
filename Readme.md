ProcessGraphQL
==============

A GraphQL for ProcessWire.

The module seamlessly integrates to your ProcessWire web app and allowa you to
serve the GraphQL api of your existing app. You don't need to apply changes to
your content or it's structure. 

Here is an example of ProcessGraphQL in action after installing it to 
[skyscrapers][pw-skyscrapers] profile.





### Usage
To use the module you need to install this module in `site/modules`
directory. Then in one of your template files you do this.
```php
$modules->get('GraphQL')->execute();
```
This will handle the GraphQL queries for you. You checkout my
[GraphiQL][graphiql] module for ProcessWire for GraphQL explorer.

[graphql]: http://graphql.org/
[pw-skyscrapers]: https://github.com/ryancramerdesign/SkyscrapersProfile