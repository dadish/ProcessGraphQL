# Todo

## Sanitize the selector

The selector string provided by user should be sanitized. Take a look at Ryan's PageService module to get clue on how to properly do that.

## N+1 Problem

- Solve N+1 problem for FieldtypeFile & FieldtypeImage.
- Solve N+1 problem for FieldtypeRepeater

## Limit the query complexity

We need to make sure the user is able to request queries only for couple levels deep to prevent the CPU intensive requests.

