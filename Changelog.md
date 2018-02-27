ProcessGraphQL Changelog
========================

### 0.22.0
- Add support for third-party Fieldtypes.

### 0.20.0
- Add support for `first` & `last` fields for PageArray types.

### 0.19.0
- Add support for `FieldtypeOptions`.

### 0.18.5
- Fix `DatetimeResolverTrait`

### 0.18.3
- Adds support for format argument for FieldtypeDatetime Including built-in
fields created & modified. Now you can pass [PHP date](https://secure.php.net/manual/en/function.date.php) formattting string and
get dates exactly how you want.

### 0.17.0
This release introduces lots of changes to repository structure and development
process.
- The master branch no longer tracks the vendor directory. This means that it
is not suitable as a ProcessWire module. Instead you need to use [latest release][latest-release]
tags as a ProcessWire module. Release branches are a minified version of this
module that includes everything needed and works out of the box. The master
branch is used for development.
- Now we have a Continious Integration implemented. [![Build Status][travis-ci-badge]][travis-ci]
This means that people can contribute confidently by running test suite and
be sure that nothing has broken and a pull-request will be merged.
- `$ProcessGraphQL->executeGraphQL` now accepts `payload` & `variables` as an
argument. This allows you to modify the payload from client and manually pass
it to the module to meet your needs.

### 0.16.0
- Add support for getQuery & getMutation hooks.

### 0.15.3
- Fix numChildren field.

### 0.15.2
- Fix the default value bug.

### 0.15.1
- Fix FieldtypeCheckbox bug.

### 0.15.0
- Fix the FieldtypeDatetime bug.
- Upgrade the youshido/graphql dependency. 

### 0.14.1
- Fix the access rules complience bug.

### 0.14.0
- Add a GraphQL pages generator

### 0.13.1
- Make sure to get request data from php://input if Content-type header contains
	`application/json` string.

### 0.13.0
- Add support for create/update mutation.
- Add support for FieldtypeMapMarker field.

### 0.12.1
- Fixed bugs

### 0.12.0
- Implemented `variations` field for PageImageType.
- Implemented `size` field for PageImageType.
- Changed security behavior. See [Access Control][module-access-control] in documentations.
- Updated documentation.

### 0.11.1
- Fixed bug for issue #2
- Updated PHP requirements for module. The module requires PHP version >= 5.5

### 0.11.0
- Implement minimal language support.

### 0.10.1
- Fix missed class import.

### 0.10.0
- Remove debug option from module settigs in favor of `$config-debug = true|false` API.
- From now only templates selected as legal and those that have Access control enabled will be served.
- Remove PageUnionType in favor of PageIntefaceType.
- Introduce UserType for pages that represent system users.
- Mark fields NonNull if they are marked as `$field->required = true`.
- Implement simple create Mutation on a per template basis.

### 0.9.1
- Fix GraphQLServerUrl property bug.

### 0.9.0
- Made GraphiQL assets load in traditional Process module way.
- Added template name change tracking support.
- Incompatible template names now cannot be selected as legalTemplates.

### 0.8.0
- Global fields now are included into PageInterface.
- Added `me` field that represents the current user.
- The built in `Page` fields are limited to essential ones and available as extra only.
- The built in `PageFile` fields are limited to essential ones and available as extra only.
- Changed the versioning to semantic. [major].[minor].[patch]

### 0.7.0
- Added authentication support.

### 0.6.0
- Added support for field permissions.
- Added option for full width GraphiQL.

### 0.5.0
- Added support for FieldtypeFile.
- Added more properties for FieldtypeImage.

### 0.4.0
- Added support for legal fields.

### 0.3.0
- Added option to restrict the api to selected page templates only.
- Added NullPageType for consistency with ProcessWire.
- Fixed some bugs.
- Added a Changelog file!


[module-access-control]: https://github.com/dadish/ProcessGraphQL/tree/master#access-control
[latest-release]: https://github.com/dadish/ProcessGraphQL/releases/latest
[travis-ci-badge]: https://www.travis-ci.org/dadish/ProcessGraphQL.svg?branch=master
[travis-ci]: https://travis-ci.org/dadish/ProcessGraphQL/