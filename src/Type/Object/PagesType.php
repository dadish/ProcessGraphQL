<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\GraphQL\Field\Pages\PagesCountField;
use ProcessWire\GraphQL\Field\Pages\PagesFindField;

class PagesType extends PageArrayType {

  public function getDescription()
  {
    return 'Enables loading and manipulation of Page objects, to and from the database.';
  }

  public function build($config)
  {
    parent::build($config);
    $config->addField(new PagesCountField());
    $config->addField(new PagesFindField());
  }

}