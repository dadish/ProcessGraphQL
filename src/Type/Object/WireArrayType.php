<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use ProcessWire\GraphQL\Field\WireArray\WireArrayFindField;
use ProcessWire\GraphQL\Field\WireArray\WireArrayCountField;

class WireArrayType extends AbstractObjectType {

  public function getName()
  {
    return 'WireArray';
  }

  public function getDescription()
  {
    return 'Base ProcessWire iterable type.';
  }

  public function build($config)
  {
    $config->addField(new WireArrayFindField());
    $config->addField(new WireArrayCountField());
  }
  
}