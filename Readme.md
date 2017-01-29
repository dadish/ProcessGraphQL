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

Documentation for your api is easily accessible via GraphiQL interface.
![ProcessGraphQL Schema Documentation][img-documentation]

## Requirements
The module is compatible only with the ProcessWire version 3.x.x and up.
There are no plans to support the older versions. 

## Installation
To install module place the contents of this directory into your `/site/modules/`
directory and go to `Setup -> Modules` in your ProcessWire admin panel and click
__Refresh__ button. You should see the ProcessGraphQL module that you can install
by clicking the __Install__ button next to it.

After you installed the ProcessGraphQL, you can go to `Setup -> GraphQL` in your
admin panel and you will see the GraphiQL where you can perform queries to your
GraphQL api.

## API
If you wish to expose your GraphQL api, you can do so by calling a single method on
ProcessGraphQL module in your template file. Here is what it might look like
```php
<?php

// /site/templates/graphql.php

echo $modules->get('ProcessGraphQL')->executeGraphQL();
```

You can also expose the GraphiQL from within your template. Here is how you can do that.
```php
<?php

// /site/templates/graphiql.php

echo $modules->get('ProcessGraphQL')->executeGraphiQL();
```
> Please note that GraphiQL is a full web page. Meaning it includes `header`,
> `title` and so on. Depending on your site configuration you might want to
> disable `$config->prependTemplateFile` and/or `$config->appendTemplateFile`
> for the template that exposes GraphiQL.

By default the GraphiQL is pointed to your admin GraphQL server, which is 
`/processwire/setup/graphql/`. You might want to change that because ProcessWire
will not allow guest users to access that url. You can point GraphiQL to whatever adress
you want by a property `GraphQLServerUrl`. ProcessGraphQL will respect that property
when exposing GraphiQL.
Here is how you might do this in your template file.
```php
// /site/templates/graphiql.php

$ProcessGraphQL = $modules->get('ProcessGraphQL');
$ProcessGraphQL->GraphQLServerUrl = '/graphql/';
echo $ProcessGraphQL->executeGraphiQL();
```

### Limitations
At this stage the module only supports the `Query` schema. There is no `Mutation` for now.
It will be implemented as soon as people will request this feature.

### Permissions
ProcessGraphQL respects the ProcessWire permissions on template level. It basicly does that
via `$user->hasPermission('page-view', $template)`. So as long as the client does not have
that permission she won't be able to query it.

## License
[MIT](https://github.com/dadish/ProcessGraphQL/blob/master/LICENSE)

[graphql]: http://graphql.org/
[graphiql]: https://github.com/graphql/graphiql/
[pw]: https://processwire.com
[pw-skyscrapers]: http://demo.processwire.com/
[pw-selectors]: https://processwire.com/api/selectors/
[img-query]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Query.gif
[img-filtering]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Filtering.gif
[img-fieldtypes]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Fieldtypes.gif
[img-documentation]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Documentation.gif