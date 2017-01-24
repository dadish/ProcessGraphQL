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

    $query->addFields([
      new PagesField()
    ]);

    $templates = \ProcessWire\wire('templates');
    $user = \ProcessWire\Wire('user');
    foreach ($templates as $template) {
      if ($user->hasPermission('page-view', $template)) {
        $query->addField(new TemplatedPageArrayField($template));
      }
    }

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