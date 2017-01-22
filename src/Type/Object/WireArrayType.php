<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use ProcessWire\GraphQL\Field\WireArray\WireArrayFindField;
use ProcessWire\GraphQL\Field\WireArray\WireArrayCountField;
use ProcessWire\GraphQL\Field\WireArray\WireArrayListField;
use ProcessWire\GraphQL\Type\Object\PagesType;
use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\Pages;
use ProcessWire\PageArray;

class WireArrayType extends AbstractObjectType {

  public function getName()
  {
    return 'WireArray';
  }

  public function getDescription()
  {
    return 'Base ProcessWire iterable interface.';
  }

  public function build($config)
  {
    $config->addField(new WireArrayCountField());
    $config->addField(new WireArrayListField());
    $config->addField(new WireArrayFindField());
  }

  public function resolveType($object)
  {
    if ($object instanceof Pages) return new PagesType();
    if ($object instanceof PageArray) return new PageArrayType();
  }
  
}