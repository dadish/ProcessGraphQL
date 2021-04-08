## [1.4.3-rc.1](https://github.com/dadish-etudes/ProcessGraphQL/compare/v1.4.2...v1.4.3-rc.1) (2021-04-08)


### Bug Fixes

* **release:** attempt to fix release config ([6467835](https://github.com/dadish-etudes/ProcessGraphQL/commit/6467835747f527159b692fc3db4a519d7110ec9c))
* **release:** prerelease on main and release on stable ([8f7675e](https://github.com/dadish-etudes/ProcessGraphQL/commit/8f7675ea1b86dde79f57528336e7ec7478c46a1a))

## [1.4.2](https://github.com/dadish-etudes/ProcessGraphQL/compare/v1.4.1...v1.4.2) (2021-04-08)

## [1.4.1](https://github.com/dadish-etudes/ProcessGraphQL/compare/v1.4.0...v1.4.1) (2021-02-27)


### Bug Fixes

* **build:** publish the module files as a release asset ([3c04dbf](https://github.com/dadish-etudes/ProcessGraphQL/commit/3c04dbf7701412f64c87eee48418e96f38b3c199))
* **build:** release with semantic-release ([f671e33](https://github.com/dadish-etudes/ProcessGraphQL/commit/f671e334585319d6218d4e3e154ee6a76305161c))
* **build:** remove the revert script ([1407ddf](https://github.com/dadish-etudes/ProcessGraphQL/commit/1407ddf4e05183d92e901442d29d989d14c05283))
* **build:** trigger another release ([6847b7a](https://github.com/dadish-etudes/ProcessGraphQL/commit/6847b7a74c7e6b6487469d12f197c0d3f60a18e6))
* **changelog:** cleanup the Changelog.md ([df7c123](https://github.com/dadish-etudes/ProcessGraphQL/commit/df7c123e5bd6ff83d1d81b5aa849eb4ab48c207c))
* **release:** append "(zip)" suffix to release asset label ([0dadc99](https://github.com/dadish-etudes/ProcessGraphQL/commit/0dadc997de641274424161aed2fedab791237887))

## [1.4.1-rc.10](https://github.com/dadish-etudes/ProcessGraphQL/compare/v1.4.1-rc.9...v1.4.1-rc.10) (2021-02-27)


### Bug Fixes

* **release:** append "(zip)" suffix to release asset label ([0dadc99](https://github.com/dadish-etudes/ProcessGraphQL/commit/0dadc997de641274424161aed2fedab791237887))

## [1.4.1-rc.9](https://github.com/dadish-etudes/ProcessGraphQL/compare/v1.4.1-rc.8...v1.4.1-rc.9) (2021-02-27)


### Bug Fixes

* **build:** publish the module files as a release asset ([3c04dbf](https://github.com/dadish-etudes/ProcessGraphQL/commit/3c04dbf7701412f64c87eee48418e96f38b3c199))
* **build:** release with semantic-release ([f671e33](https://github.com/dadish-etudes/ProcessGraphQL/commit/f671e334585319d6218d4e3e154ee6a76305161c))
* **build:** remove the revert script ([1407ddf](https://github.com/dadish-etudes/ProcessGraphQL/commit/1407ddf4e05183d92e901442d29d989d14c05283))
* **build:** trigger another release ([6847b7a](https://github.com/dadish-etudes/ProcessGraphQL/commit/6847b7a74c7e6b6487469d12f197c0d3f60a18e6))
* **changelog:** cleanup the Changelog.md ([df7c123](https://github.com/dadish-etudes/ProcessGraphQL/commit/df7c123e5bd6ff83d1d81b5aa849eb4ab48c207c))

## [1.0.2] - 2019-11-19

### Added

- `trash(id: ID!): Page!` field allows to move pages to trash via GraphQL api.
- Solves N+1 problem for FieldtypePage field. Significantly improves response speed!
- Support for `FieldtypeRepeater`.
- Support for even more ProcessWire permissions:
  - `page-add`
  - `page-create`
  - `page-delete`
  - `page-edit`
  - `page-move`
  - `page-view`
  - `page-edit-created`
  - `page-edit-trash-created`

### Changed

- The module was rewritten to use [webonyx/graphql-php](https://github.com/webonyx/graphql-php) instead of [youshido/graphql](https://github.com/youshido/graphql).
- `ProcessGraphQL->executeGraphQL` now returns an array. You'll need to convert it to JSON before sending it to the client. You can use `json_encode` php method for that.
- "updatePage" fields no longer accept the `id` argument. You have to pass the page's id you want to update into page argument of the "updatePage" field.
- The lowest version of PHP is 7.1 is required.
- If you had used a third-party module to support additional PW fields for GraphQL, then they are no longer going to work. You'll have to update them to use [webonyx/graphql-php](https://github.com/webonyx/graphql-php).
- If you used `GraphqlFieldtypeMapMarker` module, you need to update it to the latest [version available](https://github.com/dadish/GraphQLFieldtypeMapMarker/archive/master.zip).

### Removed

- The following fields are removed and no longer will be available for Page object types.
  - `find`
  - `next`
  - `prev`
  - `rootParent`
  - `siblings`
- An experimental `pages` field is dropped and no longer supported.
- No more __Grant Templates Access__ and __Grant Fields Access__ options. You now have to explicitly set access permissions to all the templates and fields you want to serve via GraphQL.

## [0.23.3] - 2018-03-01

### Fixed

- Fix module installation via class name from pw modules directory.

## [0.23.0] - 2018-02-28

### Changed

- Simpler naming for GraphQL types.
- Update GraphiQL js dependencies.

## [0.22.0] - 2018-02-28

### Added

- Add support for third-party Fieldtypes.

## [0.20.0] - 2018-02-23

### Added

- Add support for `first` & `last` fields for PageArray types.

## [0.19.0] - 2018-02-22

### Added

- Add support for `FieldtypeOptions`.

## [0.18.5] - 2018-02-16

### Fixed

- Fix `DatetimeResolverTrait`

## [0.18.3] - 2018-02-16

### Added

- Adds support for format argument for FieldtypeDatetime Including built-in fields created & modified. Now you can pass [PHP date](https://secure.php.net/manual/en/function.date.php) formattting string and get dates exactly how you want.

## [0.17.0] - 2018-02-14

This release introduces lots of changes to repository structure and development process.

### Changed

- The main branch no longer tracks the vendor directory. This means that it is not suitable as a ProcessWire module. Instead you need to use [latest release][latest-release] tags as a ProcessWire module. Release branches are a minified version of this module that includes everything needed and works out of the box. The main branch is used for development.
- Now we have a Continious Integration implemented. [![Build Status][travis-ci-badge]][travis-ci] This means that people can contribute confidently by running test suite and be sure that nothing has broken and a pull-request will be merged.
- `$ProcessGraphQL->executeGraphQL` now accepts `payload` & `variables` as an argument. This allows you to modify the payload from client and manually pass it to the module to meet your needs.

## [0.16.0] - 2017-04-09

### Added

- Add support for getQuery & getMutation hooks.

## [0.15.3] - 2017-04-07

- Fix numChildren field.

## [0.15.2] - 2017-04-07

### Fixed

- Fix the default value bug.

## [0.15.1] - 2017-04-04

### Fixed

- Fix FieldtypeCheckbox bug.

## [0.15.0] - 2017-04-01

### Changed

- Upgrade the youshido/graphql dependency.

### Fixed

- Fix the FieldtypeDatetime bug.

## [0.14.1] - 2017-03-23

### Fixed

- Fix the access rules complience bug.

## [0.14.0] - 2017-03-22

### Added

- Add a GraphQL pages generator

## [0.13.1] - 2017-03-21

### Added

- Make sure to get request data from php://input if Content-type header contains `application/json` string.

## [0.13.0] - 2017-03-19

### Added

- Add support for create/update mutation.
- Add support for FieldtypeMapMarker field.

## [0.12.1] - 2017-03-19

### Fixed

- Fixed bugs

## [0.12.0] - 2017-03-19

### Added

- Implemented `variations` field for PageImageType.
- Implemented `size` field for PageImageType.

### Changed

- Changed security behavior. See [Access Control][module-access-control] in documentations.
- Updated documentation.

## [0.11.1] - 2017-03-19

### Changed

- Updated PHP requirements for module. The module requires PHP version >= 5.5

### Fixed

- Fixed bug for issue #2

## [0.11.0] - 2017-03-19

### Added

- Implement minimal language support.

## [0.10.1] - 2017-03-19

### Fixed

- Fix missed class import.

## [0.10.0] - 2017-03-19

### Added

- Introduce UserType for pages that represent system users.
- Implement simple create Mutation on a per template basis.

### Changed

- From now only templates selected as legal and those that have Access control enabled will be served.
- Mark fields NonNull if they are marked as `$field->required = true`.

### Removed

- Remove debug option from module settigs in favor of `$config-debug = true|false` API.
- Remove PageUnionType in favor of PageIntefaceType.

## [0.9.1] - 2017-03-19

### Fixed

- Fix GraphQLServerUrl property bug.

## [0.9.0] - 2017-03-19

### Added

- Added template name change tracking support.

### Changed

- Made GraphiQL assets load in traditional Process module way.
- Incompatible template names now cannot be selected as legalTemplates.

## [0.8.0] - 2017-03-17

### Added

- Added `me` field that represents the current user.

### Changed

- Global fields now are included into PageInterface.
- The built in `Page` fields are limited to essential ones and available as extra only.
- The built in `PageFile` fields are limited to essential ones and available as extra only.
- Changed the versioning to semantic. [major].[minor].[patch]

## [0.7.0]

### Added

- Added authentication support.

## [0.6.0]

### Added

- Added support for field permissions.
- Added option for full width GraphiQL.

## [0.5.0]

### Added

- Added support for FieldtypeFile.
- Added more properties for FieldtypeImage.

## [0.4.0]

### Added

- Added support for legal fields.

## [0.3.0]

### Added

- Added option to restrict the api to selected page templates only.
- Added NullPageType for consistency with ProcessWire.
- Added a Changelog file!

### Changed

- Fixed some bugs.

[module-access-control]: https://github.com/dadish/ProcessGraphQL/tree/main#access-control
[latest-release]: https://github.com/dadish/ProcessGraphQL/releases/latest
[webonyx/graphql-php]: https://github.com/webonyx/graphql-php
