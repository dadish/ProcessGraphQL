ProcessGraphQL Changelog
========================

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