<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Field\Pages\PagesCountField;

class PagesType extends AbstractObjectType {

  public function build($config)
  {
    $config->addField(new PagesCountField());
  }

  public function getDescription()
  {
    return 'Enables loading and manipulation of Page objects, to and from the database.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return [];
  }

}