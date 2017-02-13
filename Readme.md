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
- ProcessWire version 3.x.x and up. There are no plans to support the older versions.
- PHP version 5.5 and up.

> It would be very helpful if you open an issue when encounter errors regarding
> environment incompatibilities.

## Installation
To install the module, go to __Modules -> Install -> Add New__. Scroll down to get
to the section __Add Module from URL__. Paste this URL into the __Module ZIP file URL__ field
`https://github.com/dadish/ProcessGraphQL/archive/master.zip` then press __Download__.
ProcessWire will download this module and place it at `/site/modules/` directory for you.
After you did that, you should see __GraphQL__ module among others. Go ahead and press __Install__
button next to it. Choose the templates and fields you want to access through your GaphQL api and
you are good to go.

After you installed the ProcessGraphQL, you can go to `Setup -> GraphQL` in your
admin panel and you will see the GraphiQL where you can perform queries to your
GraphQL api.

## Configuration
There are some options to configure the ProcessGraphQL module.

### MaxLimit
The MaxLimit option allows you to set the ProcessWire's [limit][pw-api-selectors-limit] slelector. So that
client is not able to more than that. While client can set values less than MaxLimit, if
she requests more it will be overwritten and set to MaxLimit. Default is 100.
#### Type
`Integer`
#### Api Property
`maxLimit`
#### Api Usage
```php
$ProcessGraphQL = $modules->get('ProcessGraphQL');
$ProcessGraphQL->maxLimit = 50;
```

### Legal Templates
Legal Templates are the templates that can be fetched via ProcessGraphQL. You have explicitly
tell ProcessGraphQL which templates you wish to declare as public api.

Please note that making a template legal does not neccessarily mean it is open to everyone.
The user permissions still apply. If you selected template __user__ as legal but the
requesting user does not have permissions to view it. She won't be able to retrieve that data.
#### Type
`Array`
#### Api Property
`legalTemplates`
#### Api Usage
```php
$ProcessGraphQL = $modules->get('ProcessGraphQL');
$ProcessGraphQL->legalTemplates = array('skyscraper', 'city', 'architect', 'basic-page');
```

### Legal Fields
Provides same functionality as for Legal Templates. Only the selected fields will be available
via GraphQL api.
#### Type
`Array`
#### Api Property
`legalFields`
#### Api Usage
```php
$ProcessGraphQL = $modules->get('ProcessGraphQL');
$ProcessGraphQL->legalFields = array('title', 'year', 'height', 'floors');
```

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
At this stage the module only supports the `Query` schema. The support for `Mutation` will come
soon.

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
[pw-api-selectors-limit]: https://processwire.com/api/selectors#limit
[img-query]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Query.gif
[img-filtering]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Filtering.gif
[img-fieldtypes]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Fieldtypes.gif
[img-documentation]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Documentation.gif
