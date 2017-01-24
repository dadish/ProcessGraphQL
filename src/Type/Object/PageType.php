<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use ProcessWire\GraphQL\Type\InterfaceType\PageType as PageInterfaceType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\FieldtypePageTitle;

class PageType extends AbstractObjectType {
  
  public function getName()
  {
    return 'Page';
  }

  public function getDescription()
  {
    return 'A ProcessWire Page object.';
  }

  public function build($config)
  {
    $config->applyInterface(new PageInterfaceType());
  }

  public function getInterfaces()
  {
      return [new PageInterfaceType()];
  }

}