<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\ProcessWire;

class Schema extends AbstractSchema {

  protected $fields = [];
  
  public function build(SchemaConfig $config)
  {
    $config->getQuery()->addFields([
      new PagesField()
    ]);
  }

}