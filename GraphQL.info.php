<?php

/**
  * getModuleInfo is a module required by all modules to tell ProcessWire about them
  *
  * @return array
  *
  */

$info = array(
  'title' => 'GraphQL',
  'version' => 002, 
  'summary' => 'GraphQL for ProcessWire.',
  'href' => 'https://github.com/dadish/pw-graphql',
  'singular' => true, 
  'autoload' => false, 
  'installs' => array('GraphiQL'),
  );