# ProcessGraphQL

[![CircleCI](https://circleci.com/gh/dadish/ProcessGraphQL.svg?style=svg)](https://circleci.com/gh/dadish/ProcessGraphQL)
[![semantic-release](https://img.shields.io/badge/%20%20%F0%9F%93%A6%F0%9F%9A%80-semantic--release-e10079.svg)](https://github.com/semantic-release/semantic-release)

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
9. [Troubleshooting](#troubleshooting)

## About ProcessGraphQL

ProcessGraphQL is a module for [ProcessWire][pw]. The module seamlessly integrates to your ProcessWire web app and allows you to serve the [GraphQL][graphql] api of your existing content. You don't need to apply changes to your content or it's structure. Just choose what you want to serve via GraphQL and your API is ready.

Here is an example of ProcessGraphQL in action after installing it to [skyscrapers][pw-skyscrapers] profile.

![ProcessGraphQL Simple Query][img-query]

See more [demo here](https://github.com/dadish/ProcessGraphQL/blob/main/ScreenCast.md).

## Requirements

- ProcessWire version >= 3.0.62
- PHP version >= 7.1

> It would be very helpful if you open an issue when encounter errors regarding environment incompatibilities.

## Installation

To install the module, go to __Modules -> Install -> Add New__. Scroll down to get to the section __Add Module from URL__. Paste the URL of the zip file of this module into the __Module ZIP file URL__ field and press __Download__. You can find the zip file of the module in the [latest release][latest-release] page. Be sure to choose the one that says `ProcessWire Module (zip)`.

[!ProcessWire Module (zip)][img-assets]

ProcessWire will download this module and place it at `/site/modules/` directory for you. After you did that, you should see __GraphQL__ module among others. Go ahead and press __Install__ button next to it.

After you installed the ProcessGraphQL, you will be taken to the module configuration page. Where you will have many options to setup the module the way you want. More on that in the section below.

## Configuration

The ProcessGraphQL module will serve only the parts of your content which you explicitly ask for. The module configuration page provides you with exactly that. Here you should choose what parts of your website should be available via GraphQL API. The options are grouped into four sections.

### Templates

In this section you choose the _templates_ that you want ProcessGraphQL to handle. The pages associated with the templates you choose here will be available to the _superuser_ immediately. You will see later how you can grant access to these template to other user roles as well.

> If some of your templates are disabled and you can't choose them. That means that their names are not compatible with [GraphQL api naming rules][graphql-naming]. You will have to change the names of the template and/or field so that it conforms those rules if you want ProcessGraphQL module to handle for you.

### Fields

Here you should choose the fields that you want to be available via GraphQL API. These fields also will immediately be available to the _superuser_.

> Beware when you choose _system_ templates & fields as legal for ProcessGraphQL module. This could potentially expose very sensitive information and undermine security of your website.

### Page Fields

These are the built-in fields of the ProcessWire Page. You should choose only the ones you will certainly need in your API. E.g. `created`, `id`, `name`, `url`, `path`, `createdUser`, `parent`, `siblings` and so on.

### PageFile Fields

Built-in fields of the FieldtypeFile and FieldtypeImage. E.g. `filesize`, `url`, `ext`, `basename` and so on.

After you chose all parts you need, submit the module configuration. Now you can go to _Setup -> GraphQL_ and you will see the GraphiQL GUI where you can query your GraphQL api. Go ahead and play with it.

## Access Control

As mentioned above, the GraphQL API will be accessible only by _superuser_. To grant access to users with different roles, you need to use __Access__ settings in your templates and fields. Say you want a user with role `skyscraper-editor` to be able to view pages with template `skyscraper`. Go to _Setup -> Templates -> skyscraper -> Access_, enable access settings, and make sure that `skyscraper-editor` role has `View Page` rule.

The above configuration will allow the `skyscraper-editor` to view `skyscraper` pages' built-in fields that you have enabled, but that's not the end of it. If you want the `skyscraper-editor` user to view the template fields like _title, headline, body_, you will need to make those fields viewable too.

Select the __Basics__ tab for `skyscraper` template and press on the field `title`. There should be a modal that allows you to configure the `title` field in the context of `skyscraper` template. Press on the __Access__ tab and enable view access for `skyscraper-editor` role and now users that have `skyscraper-editor` role can view the `title` field of the `skyscraper` template.

The ProcessWire's Access Control system is very flexible and allows you to fine tune your access rules the way you want. You will use it to control access to your GraphQL API as well. ProcessGraphQL treats permissions exactly the way ProcessWire does. Below is the list of permissions supported by ProcessGraphQL.

- `page-add`
- `page-create`
- `page-delete`
- `page-edit`
- `page-move`
- `page-view`
- `page-edit-created`
- `page-edit-trash-created`

Learn more about ProcessWire's Access Control system [here][pw-access].

## API

### GraphQL endpoint

If you wish to expose your GraphQL api, you can do so by calling a single method on ProcessGraphQL module in your template file. Here is what it might look like

```php
<?php
// /site/templates/graphql.php

$result = $modules->get('ProcessGraphQL')->executeGraphQL();
echo json_encode($result);
```

This will automatically capture the GraphQL request from your client and respond to it. If you need some manual control on this `executeGraphQL` accepts `$query` & `$variables` arguments and it will respond to them instead of trying to guess query from the client. This allows you to modify the request from the client before passing it to ProcessGraphQL. Here how it might look like.

```php
<?php
// /site/templates/graphql.php

$query = $input->post->query;
$variables = $input->post->variables;

// modify your $query and $variables here...

$result = $modules->get('ProcessGraphQL')->executeGraphQL($query, $variables);
echo json_encode($result);
```

### GraphiQL endpoint

You can also expose the GraphiQL from within your template. Here is how you can do that.

```php
<?php
// /site/templates/graphiql.php

echo $modules->get('ProcessGraphQL')->executeGraphiQL();
```

> Please note that GraphiQL is a full web page. Meaning it includes `<header>`, `<title>` and so on. Depending on your site configuration you might want to disable `$config->prependTemplateFile` and/or `$config->appendTemplateFile` for the template that exposes GraphiQL.

By default the GraphiQL is pointed to your admin GraphQL server, which is `/processwire/setup/graphql/`. You might want to change that because ProcessWire will not allow guest users to access that url. You can point GraphiQL to whatever adress you want by a property `GraphQLServerUrl`. ProcessGraphQL will respect that property when exposing GraphiQL. Here is how you might do this in your template file.

```php
<?php

// /site/templates/graphiql.php

$ProcessGraphQL = $modules->get('ProcessGraphQL');
$ProcessGraphQL->GraphQLServerUrl = '/graphql/';
echo $ProcessGraphQL->executeGraphiQL();
```

> Make sure the url is exactly where your GraphQL api is. E.g. it ends with slash. See [here](https://github.com/dadish/ProcessGraphQL/issues/1) why it is important.

### Modifying Query and Mutation

There could be cases when you want to include some custom fields into your GraphQL query and mutation operation. There are two ProcessWire hooks that allows you to do that.

#### getQueryFields() hook

You can hook into `getQuery` method of the `ProcessGraphQL` class to add some custom fields into your GraphQL query operation. Here how it could look like in your `graphql.php` template file.

```php
<?php namespace ProcessWire;

use GraphQL\Type\Definition\Type;

$processGraphQL = $modules->get('ProcessGraphQL');

wire()->addHookAfter("ProcessGraphQL::getQueryFields", function (
  $event
) {
  $fields = $event->return;
  $fields[] = [
    "name" => "hello",
    "type" => Type::string(),
    "resolve" => function () {
      return "world!";
    },
  ];
  $event->return = $fields;
});

echo $processGraphQL->executeGraphQL();
```

The above code will add a `hello` field into your GraphQL api that reponds with the string `world`. You should notice that we use third party library to modify our query. It's the  library used by ProcessGraphQL internally. We recommend you to checkout the [library documentation][webonyx-graphql] to learn more about how you can modify your GraphQL api.

#### getMutation() hook

You can also hook into `getMutationFields` method of `ProcessGraphQL` class to add custom fields into your GraphQL mutation operation. It works exactly like the `getQuery` hook method.

## Features

### GraphQL Operations

The module will eventually support all operations you need to build fully functioning SPA. For now you can perform most common operations.

- Fetch pages, page fields, subfields. Including file and image fields.
- Authenticate. You can login and logout with your GraphQL API.
- Page creation. You can create pages via GraphQL API.
- Language support. If your site uses ProcessWire's core LanguageSupport module, you can fetch data in your desired language.
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
- FieldtypeRepeater

### Third-party Fieldtypes Support

You can add support for any third-party fieldtype by creating a module for it. The example module that you can refer to is [GraphQLFieldtypeMapMarker][map-marker-graphql] that adds support for FieldtypeMapMarker fieldtype. Below are the basic requirements that this kind of modules should fulfill.

#### Naming of the Module

Name your module exactly as the Fieldtype module you are adding support for with `GraphQL` prefix. So for example `GraphQLFieldtypeMapMarker` adds support for `FieldtypeMapMarker`.

#### Required methods

There are three required methods.

##### public static function getType(Field $field)

The value type that the fieldtype returns. Could be string, number, boolean or an abject with bunch of subfields.

##### public static function getInputType(Field $field)

The value type that the fieldtype accepts. Could be different value type than it returns. For instance FieldtypePage returns a Page object with lots of subfields, but can accept a simple integer (id of the page) as a value.

##### public static function setValue(Page $page, Field $field, $value)

Given the `$page`, `$field` and a `$value`, the method sets the value to the page's given field.

> Note: The GraphQL api is built upon [webonyx/graphql-php][webonyx-graphql] library. So the methods above should be built using that library. Please see [GraphQLFieldtypeMapMarker][map-marker-graphql] module for reference.

When your module is ready, just install it and it should be automatically used by ProcessGraphQL and your fieldtype should be available via your GraphQL api.

## Troubleshooting

### Syntax Error: Unexpected &lt;EOF&gt;

If you are getting an error response from your GraphQL API with the following structure

```json
{
  "errors": [
    {
      "message": "Syntax Error: Unexpected <EOF>",
      "extensions": {
        "category": "graphql"
      },
      "locations": [
        {
          "line": 1,
          "column": 1
        }
      ]
    }
  ]
}
```

then it is probably because ProcessGraphQL is not receiving your query. The reason for this could be that the url you're making a request to does not end with `/` (forward slash). In ProcessWire, the urls `/graphql` and `/graphql/` are treated differently. If you are making a request to `/graphql` (without forward slash at the end) the ProcessWire could be redirecting to `/graphql/` (with forward slash at the end) instead of passing it to your template. When your request is redirected from `/graphql` to `/graphql/` the content of your POST is being lost in the middle and never reaches your `graphql.php` template. So make sure the url that you're making a request to is exactly what you intend it to be.

## License

[MIT](https://github.com/dadish/ProcessGraphQL/blob/main/LICENSE)

[graphql]: http://graphql.org/
[graphql-naming]: http://facebook.github.io/graphql/#sec-Names
[graphiql]: https://github.com/graphql/graphiql/
[pw]: https://processwire.com
[pw-skyscrapers]: http://demo.processwire.com/
[pw-selectors]: https://processwire.com/api/selectors/
[pw-access]: http://processwire.com/api/user-access/
[pw-api-selectors-limit]: https://processwire.com/api/selectors#limit
[img-query]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/main/imgs/ProcessGraphQL-Query.gif
[img-filtering]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/main/imgs/ProcessGraphQL-Filtering.gif
[img-fieldtypes]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/main/imgs/ProcessGraphQL-Fieldtypes.gif
[img-documentation]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/main/imgs/ProcessGraphQL-Documentation.gif
[img-assets]: https://raw.githubusercontent.com/dadish/ProcessGraphQL/main/imgs/module-files.png
[latest-release]: https://github.com/dadish/ProcessGraphQL/releases/latest
[map-marker-graphql]: https://github.com/dadish/GraphQLFieldtypeMapMarker
[webonyx-graphql]: https://github.com/webonyx/graphql-php
