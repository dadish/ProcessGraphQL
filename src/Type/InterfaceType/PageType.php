<?php

namespace ProcessWire\GraphQL\Type\InterfaceType;

use Youshido\GraphQL\Type\InterfaceType\AbstractInterfaceType;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;

class PageType extends AbstractInterfaceType {

  public function getName()
  {
    return 'PageInterface';
  }

  public function getDescription()
  {
    return 'Base ProcessWire Page interface.';
  }

  public function build($config)
  {
    $pageTypeFieldClassNames = [
      'PageChildField',
      'PageChildrenField',
      'PageCreatedField',
      'PageCreatedUserField',
      'PageFindField',
      'PageHttpUrlField',
      'PageIdField',
      'PageModifiedField',
      'PageModifiedUserField',
      'PageNameField',
      'PageNextField',
      'PageNumChildrenField',
      'PageParentField',
      'PageParentIdField',
      'PageParentsField',
      'PagePathField',
      'PagePrevField',
      'PageRootParentField',
      'PageSiblingsField',
      'PageUrlField',
    ];
    foreach ($pageTypeFieldClassNames as $pageTypeFieldClassName) {
      $className = "ProcessWire\\GraphQL\\Field\\Page\\$pageTypeFieldClassName";
      $config->addField(new $className());
    }
  }

  public function resolveType($page)
  {
    return new PageObjectType();
  }

}