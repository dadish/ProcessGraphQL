<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use ProcessWire\GraphQL\Settings;
use ProcessWire\GraphQL\Field\Pages\PagesField;
use ProcessWire\GraphQL\Field\TemplatedPageArray\TemplatedPageArrayField;
use ProcessWire\GraphQL\Field\Debug\DbQueryCountField;
use ProcessWire\GraphQL\Field\Auth\LoginField;



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
      $query->addField(new DbQueryCountField());
    }

    // Auth
    $query->addfield(new LoginField());
  }

  public function getName()
  {
    return 'Root';
  }

}