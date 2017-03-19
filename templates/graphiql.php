<?php namespace ProcessWire;

$ProcessGraphQL = $modules->get('ProcessGraphQL');
$ProcessGraphQL->GraphQLServerUrl = '/graphql/';
echo $ProcessGraphQL->executeGraphiQL();