#### Sanitize the selector
The selector string provided by user should be sanitized. Take a look at Ryan's
PageService module to get clue on how to properly do that.

#### Template name incompatibility
We map ProcessWire templates as types in GraphQL and create fields that corrspond
to them. The name of those fields are the names of the templates. While ProcessWire
allows us to create template names that start with numbers and has '-' symbol in it
while GraphQL does not allows that. Thus we need to make sure user cannot assign
incompatible templates names as legalTemplates in ProcessGraphQL by disabling the
checkbox choice for the incompatible templates and adding some note for user describing
what's happening. We alos need to track the template name change and remove the templates
from legalTemplates when their name change to incompatible ones.

#### Template name collision
We provide additional fields with our GraphQL api like `me`, `debug`, `pages` and
probably more. Those names will be overwritten by template fields that has the same
name. This should be prevented or at least the user should be warned about it.
There probably should be list of reserved words for it, to prevent breaking changes
in the future.

#### N+1 Problem
It is very convinient to request additional data for Page fields. E.g. request skyscraper'same
title, year, height and also request title for each page in architect field. While this works
currently it will make ProcessWire make [N+1 requests][n1-problem] to database and make
response slow. We need to solve it.

#### Limit the query complexity
We need to make sure the user is able to request queries only for couple levels deep
to prevent the CPU intensive requests.



[n1-problem]: https://secure.phabricator.com/book/phabcontrib/article/n_plus_one/