<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use ProcessWire\GraphQL\Field\Pages\Pages;

class Schema extends AbstractSchema {

  protected $fields = [];
  
  public function build(SchemaConfig $config)
  {
    $config->getQuery()->addFields([
      new Pages()
    ]);
  }

  public function getName()
  {
    return 'Root';
  }

}