ProcessGraphQL
==============

[![Travis-CI Status][travis-ci-badge]][travis-ci]

[GraphQL][graphql] for [ProcessWire][pw].

## Table of Contents
1. [About](#about-processgraphql)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [Access Control](#access-control)
6. [API](#api)
7. [Features](#features)
8. [Development](https://github.com/dadish/ProcessGraphQL/wiki/Development)

## About ProcessGraphQL
ProcessGraphQL is a module for [ProcessWire][pw]. The module seamlessly integrates to your ProcessWire 
web app and allows you to serve the [GraphQL][graphql] api of your existing content. You don't need to apply 
changes to your content or it's structure. Just choose what you want to serve via GraphQL and your 
API is ready.

Here is an example of ProcessGraphQL in action after installing it to 
[skyscrapers][pw-skyscrapers] profile.

![ProcessGraphQL Simple Query][img-query]

See more [demo here](https://github.com/dadish/ProcessGraphQL/blob/master/ScreenCast.md).

## Requirements
- ProcessWire version >= 3.0.62
- PHP version >= 7.1

> It would be very helpful if you open an issue when encounter errors regarding 
> environment incompatibilities.

## Installation
To install the module, go to __Modules -> Install -> Add New__. Scroll down to get to the section 
__Add Module from URL__. Paste the URL of the [latest release][latest-release] into the __Module ZIP file 
URL__ field and press __Download__.

ProcessWire will download this module and place it at `/site/modules/` directory for you. After you 
did that, you should see __GraphQL__ module among others. Go ahead and press __Install__ button next 
to it.

After you installed the ProcessGraphQL, you will be taken to the module configuration page. Where you 
will have many options to setup the module the way you want. More on that in the section below.

## Configuration
The ProcessGraphQL module will serve only the parts of your content which you explicitly ask for. 
The module configuration page provides you with exactly that. Here you should choose what parts of 
your website should be available via GraphQL API. The options are grouped into four sections.

#### Legal Templates
In this section you choose the _templates_ that you want ProcessGraphQL to handle. The pages associated 
with the templates you choose here will be available to the _superuser_ immediately. You will see later 
how you can grant access to these template to other user roles as well.

> If some of your templates are disabled and you can't choose them. That means that their names are not 
> compatible with [GraphQL api naming rules][graphql-naming]. You will have to change the names of the template and/or 
> field so that it conforms those rules if you want ProcessGraphQL module to handle for you.

#### Legal Fields
Here you should choose the fields that you want to be available via GraphQL API. These fields also 
will immediately be available to the _superuser_.

> Beware when you choose _system_ templates & fields as legal for ProcessGraphQL module. This could 
> potentially expose very sensitive information and undermine security of your website.

#### Legal Page Fields
These are the built-in fields of the ProcessWire Page. You should choose only the ones you will 
certainly need in your API. E.g. `created`, `id`, `name`, `url`, `path`, `createdUser`, `parent`, 
`siblings` and so on.

#### Legal PageFile Fields
Built-in fields of the FieldtypeFile and FieldtypeImage. E.g. `filesize`, `url`, `ext`, `basename` 
and so on.

Don't mind the __Advanced__ section for now. We will come to that later. After you chose all parts 
you need, submit the module configuration. Now you can go to _Setup -> GraphQL_ and you will see 
the GraphiQL GUI where you can query your GraphQL api. Go ahead and play with it.

## Access Control
As mentioned above, the GraphQL API will be accessible only by _superuser_. To grant access to users 
with different roles, you need to use __Access__ settings in your templates and fields. Say you want a 
user with role `skyscraper-editor` to be able to view pages with template `skyscraper`. Go to _Setup -> 
Templates -> skyscraper -> Access_, enable access settings, and make sure that `skyscraper-editor` 
role has `View Page` rule.

> The ProcessWire'a Access Control system is very flexible and allows you to fine tune your access rules 
> the way you want. You will use it to control your GraphQL API as well. Learn more about ProcessWire's 
> Access Control system [here][pw-access].

The above configuration will allow the `skyscraper-editor` to view `skyscraper` pages' built-in fields 
that you have enabled, but that's not the end of it. If you want the `skyscraper-editor` user to view 
the template fields like _title, headline, body_, you will need to make those fields viewable in 
their respective __Access__ settings.

You might say that this much restriction is too much. It is true, but no worries we got you covered. 
This is just the default behavior and set that way to ensure maximum security. If you don't want to 
go over fields' Access settings and setup rules for each of them manually, you can change the module's 
behavior in the __Advanced__ section of the module configuration page. There are two options that change 
the module's behavior regarding security.

#### Grant Template Access
If you check this field, all the legal templates that do not have their __Access__ settings enabled, will 
be available to everyone. But they will still conform to __Access__ settings when they are enabled. 
So you can restrict each template via their __Access__ settings.

#### Grant Field Access
This works the same as the above. Grants access to all fields that do not have __Access__ settings 
enabled. This option could be useful in cases where you have 20 or something fields and you want 
all of them be accessible and add restrictions to only few via field's Access settings. Remember that 
you can also configure field Access rules in template context. That means you can make _images_ field 
viewable in _skyscraper_ template and closed in others.

## API
### GraphQL endpoint
If you wish to expose your GraphQL api, you can do so by calling a single method on ProcessGraphQL module 
in your template file. Here is what it might look like
```php
<?php
// /site/templates/graphql.php

echo $modules->get('ProcessGraphQL')->executeGraphQL();
```
This will automatically capture the GraphQL request from your client and respond to it. If you need 
some manual control on this, `executeGraphQL` accepts `$query` & `$variables` arguments and it will 
respond to them instead of trying to guess query from the client. This allows you to modify the 
request from the client before passing it to ProcessGraphQL. Here how it might look like.
```php
<?php
// /site/templates/graphql.php

$query = $input->post->query;
$variables = $input->post->variables;
...
// modify your $query and $variables here...
...
$result = $modules->get('ProcessGraphQL')->executeGraphQL($query, $variables);
echo $result;
```

### GraphiQL endpoint
You can also expose the GraphiQL from within your template. Here is how you can do that.
```php
<?php
// /site/templates/graphiql.php

echo $modules->get('ProcessGraphQL')->executeGraphiQL();
```
> Please note that GraphiQL is a full web page. Meaning it includes `<header>`, `<title>` and so on. 
> Depending on your site configuration you might want to disable `$config->prependTemplateFile` 
> and/or `$config->appendTemplateFile` for the template that exposes GraphiQL.

By default the GraphiQL is pointed to your admin GraphQL server, which is `/processwire/setup/graphql/`. 
You might want to change that because ProcessWire will not allow guest users to access that url. 
You can point GraphiQL to whatever adress you want by a property `GraphQLServerUrl`. ProcessGraphQL 
will respect that property when exposing GraphiQL. Here is how you might do this in your template file.
```php
<?php

// /site/templates/graphiql.php

$ProcessGraphQL = $modules->get('ProcessGraphQL');
$ProcessGraphQL->GraphQLServerUrl = '/graphql/';
echo $ProcessGraphQL->executeGraphiQL();
```
> Make sure the url is exactly where your GraphQL api is. E.g. it ends with slash. 
> See [here](https://github.com/dadish/ProcessGraphQL/issues/1) why it is important.

### Modifying Query and Mutation
There could be cases when you want to include some custom fields into your GraphQL query and mutation 
operation. There are two ProcessWire hooks that allows you to do that.
#### getQuery() hook
You can hook into `getQuery` method of the `ProcessGraphQL` class to add some custom fields into your 
GraphQL query operation. Here how it could look like in your `graphql.php` template file.
```php
<?php namespace ProcessWire;

use Youshido\GraphQL\Type\Scalar\StringType;

$processGraphQL = $modules->get('ProcessGraphQL');

wire()->addHookAfter('ProcessGraphQL::getQuery', function ($event) {
    $query = $event->return;
    $query->addField('hello', [
        'type' => new StringType(),
        'resolve' => function () {
            return 'world!';
        }
    ]);
});

echo $processGraphQL->executeGraphQL();
```
The above code will add a `hello` field into your GraphQL api that reponds with the string `world`. 
You should notice that we use third party library `Youshido\GraphQL` to modify our query. It's the 
library used by ProcessGraphQL internally. We recommend you to checkout the [library documentation][youshido-graphql] 
to learn more about how you can modify your GraphQL api.

#### getMutation() hook
You can also hook into `getMutation` method of `ProcessGraphQL` class to add custom fields into your 
GraphQL mutation operation. It works exactly like the `getQuery` hook method.

## Features
### GraphQL Operations
The module will eventually support all operations you need to build fully functioning SPA. For now 
you can perform most common operations.
- Fetch pages, page fields, subfields. Including file and image fields.
- Authenticate. You can login and logout with your GraphQL API.
- Page creation. You can create pages via GraphQL API.
- Language support. If your site uses ProcessWire's core LanguageSupport module, you can fetch data
  in your desired language.
- `me` field. Allows the user to query her credentials.
- `pages` field. _Experimental_. Allows you to fetch any page in your website, just like
  `$pages->find()`

### Compatible Fields
At this moment ProcessGraphQL handles most of the ProcessWire's core fieldtypes. Those are:
- FieldtypeCheckbox
- FieldtypeDatetime
- FieldtypeEmail
- FieldtypeFile
- FieldtypeFloat
- FieldtypeImage
- FieldtypeInteger
- FieldtypeOptions
- FieldtypePage
- FieldtypePageTitle
- FieldtypePageTitleLanguage
- FieldtypeSelector
- FieldtypeText
- FieldtypeTextLanguage
- FieldtypeTextarea
- FieldtypeTextareaLanguage
- FieldtypeURL
- FieldtypeMapMarker (via [GraphQLFieldtypeMapMarker][map-marker-graphql])

### Third-party Fieldtypes Support
You can add support for any third-party fieldtype by creating a module for it. The example module 
that you can refer to is [GraphQLFieldtypeMapMarker][map-marker-graphql] that adds support for FieldtypeMapMarker 
fieldtype. Below are the basic requirements that this kind of modules should fulfill.

#### Naming of the Module
Name your module exactly as the Fieldtype module you are adding support for with `GraphQL` prefix. 
So for example `GraphQLFieldtypeMapMarker` adds support for `FieldtypeMapMarker`.

#### Required methods
There are three required methods.

##### public static function getType(Field $field)
The value type that the fieldtype returns. Could be string, number, boolean or an abject with bunch 
of subfields.

##### public static function getInputType(Field $field)
The value type that the fieldtype accepts. Could be different value type than it returns. For 
instance FieldtypePage returns a Page object with lots of subfields, but can accept a simple 
integer (id of the page) as a value.

##### public static function setValue(Page $page, Field $field, $value)
Given the `$page`, `$field` and a `$value`, the method sets the value to the page's given field.

> Note: The GraphQL api is built upon [Youshido/GraphQL][youshido-graphql] library. So the methods above should 
> be built using that library. Please see [GraphQLFieldtypeMapMarker][map-marker-graphql] module for reference.

When your module is ready, just install it and it should be automatically used by ProcessGraphQL and 
your fieldtype should be available via your GraphQL api.


## License
[MIT](https://github.com/dadish/ProcessGraphQL/blob/master/LICENSE)

[graphql]: http://graphql.org/
[graphql-naming]: http://facebook.github.io/graphql/#sec-Names
[graphiql]: https://github.com/graphql/graphiql/
[pw]: https://processwire.com
[pw-skyscrapers]: http://demo.processwire.com/
[pw-selectors]: https://processwire.com/api/selectors/
[pw-access]: http://processwire.com/api/user-access/
[pw-api-selectors-limit]: https://processwire.com/api/selectors#limit
[img-query]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Query.gif
[img-filtering]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Filtering.gif
[img-fieldtypes]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Fieldtypes.gif
[img-documentation]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/master/imgs/ProcessGraphQL-Documentation.gif
[youshido-graphql]: https://github.com/Youshido/GraphQL
[travis-ci-badge]: https://www.travis-ci.org/dadish/ProcessGraphQL.svg?branch=master
[travis-ci]: https://travis-ci.org/dadish/ProcessGraphQL/
[latest-release]: https://github.com/dadish/ProcessGraphQL/releases/latest
[map-marker-graphql]: https://github.com/dadish/GraphQLFieldtypeMapMarker
[youshido-graphql]: https://github.com/youshido/graphql
