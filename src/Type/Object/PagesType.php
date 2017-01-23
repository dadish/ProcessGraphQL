<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\GraphQL\Field\Pages\PagesCountField;
use ProcessWire\GraphQL\Field\Pages\PagesFindField;

class PagesType extends PageArrayType {

  public function getName()
  {
    return 'PagesType';
  }

  public function getDescription()
  {
    return 'Represents ProcessWire `Pages` class.';
  }

  public function build($config)
  {
    $config->addField(new PagesCountField());
    $config->addField(new PagesFindField());
  }

}