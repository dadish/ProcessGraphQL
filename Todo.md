#### Sanitize the selector
The selector string provided by user should be sanitized. Take a look at Ryan's
PageService module to get clue on how to properly do that.

#### N+1 Problem
It is very convenient to request additional data for Page fields. E.g. request skyscraper's name
title, year, height and also request title for each page in architect field. While this works
currently it will make ProcessWire make [N+1 requests][n1-problem] to database and make
response slow. We need to solve it.

#### Limit the query complexity
We need to make sure the user is able to request queries only for couple levels deep
to prevent the CPU intensive requests.

#### Create a dedicated type for Page ids.
Currently there is no special type for identifying a page id. We are just using a `String` type right now. But it would be better if we used some kind of `Id` type. So it is easier to change it's behavior accross codebase if we need.

[n1-problem]: https://secure.phabricator.com/book/phabcontrib/article/n_plus_one/
