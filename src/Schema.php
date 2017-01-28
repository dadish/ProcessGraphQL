<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use ProcessWire\GraphQL\Field\Pages\PagesField;
use ProcessWire\GraphQL\Field\TemplatedPageArray\TemplatedPageArrayField;
use ProcessWire\GraphQL\Field\Debug\DbQueryCountField;
use ProcessWire\GraphQL\Settings;


class Schema extends AbstractSchema {

  protected $fields = [];
  
  public function build(SchemaConfig $config)
  {
    $query = $config->getQuery();

    // $pages API
    $query->addField(new PagesField());
    
    // $templates
    foreach (Settings::getLegalTemplates() as $template) {
      $query->addField(new TemplatedPageArrayField($template));
    }

    // Debugging
    if (Settings::module()->debug) {
      $query->addFields([
        new DbQueryCountField()
      ]);
    }
  }

  public function getName()
  {
    return 'Root';
  }

}