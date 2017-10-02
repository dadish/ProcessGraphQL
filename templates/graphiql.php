<?php namespace ProcessWire;

$ProcessGraphQL = $modules->get('ProcessGraphQL');
$ProcessGraphQL->GraphQLServerUrl = $config->urls->root . '/graphql/';
echo $ProcessGraphQL->executeGraphiQL();
