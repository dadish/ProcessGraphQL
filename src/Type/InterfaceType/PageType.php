<?php

namespace ProcessWire\GraphQL\Type\InterfaceType;

use Youshido\GraphQL\Type\InterfaceType\AbstractInterfaceType;
use ProcessWire\Field;
use ProcessWire\GraphQL\Type\Object\TemplatedPageType;
use ProcessWire\GraphQL\Settings;

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

    // add global fields too
    $legalFields = Settings::getLegalFields();
    foreach ($legalFields as $field) {
      if ($field->flags & Field::flagGlobal) {
        $className = "\\ProcessWire\\GraphQL\\Field\\Page\\Fieldtype\\" . $field->type->className();
        if (!class_exists($className)) continue;
        $config->addField(new $className($field));
      }
    }
  }

  public function resolveType($page)
  {
    return new TemplatedPageType($page->template);
  }

}