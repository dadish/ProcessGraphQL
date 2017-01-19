<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use ProcessWire\GraphQL\Field\Pages\PagesField;

class Schema extends AbstractSchema {

  protected $fields = [];
  
  public function build(SchemaConfig $config)
  {
    $config->getQuery()->addFields([
      new PagesField()
    ]);
  }

  public function getName()
  {
    return 'Root';
  }

}