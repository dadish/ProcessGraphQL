<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use ProcessWire\GraphQL\Field\Pages\PagesField;

use ProcessWire\GraphQL\Settings;

use ProcessWire\GraphQL\Field\Debug\DbQueryCountField;

class Schema extends AbstractSchema {

  protected $fields = [];
  
  public function build(SchemaConfig $config)
  {
    $config->getQuery()->addFields([
      new PagesField()
    ]);

    if (Settings::module()->debug) {
      $config->getQuery()->addFields([
        new DbQueryCountField()
      ]);
    }
  }

  public function getName()
  {
    return 'Root';
  }

}