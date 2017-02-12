<?php

namespace ProcessWire\GraphQL\Type\InterfaceType;

use Youshido\GraphQL\Type\InterfaceType\AbstractInterfaceType;
use ProcessWire\GraphQL\Utils;
use ProcessWire\Field;
use ProcessWire\GraphQL\Type\Object\TemplatedPageType;

class PageInterfaceType extends AbstractInterfaceType {

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
    $fields = self::getPageFields();
    $legalPageFields = Utils::moduleConfig()->legalPageFields;
    
    foreach ($fields as $fieldName => $fieldClassName) {
      if (!in_array($fieldName, $legalPageFields)) continue;
      $className = "ProcessWire\\GraphQL\\Field\\Page\\$fieldClassName";
      $config->addField(new $className());
    }

    // add global fields too
    $legalFields = Utils::moduleConfig()->legalFields;
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

  public static function getPageFields()
  {
    return [
      'child' => 'PageChildField',
      'children' => 'PageChildrenField',
      'created' => 'PageCreatedField',
      'createdUser' => 'PageCreatedUserField',
      'find' => 'PageFindField',
      'httpUrl' => 'PageHttpUrlField',
      'id' => 'PageIdField',
      'modified' => 'PageModifiedField',
      'modifiedUser' => 'PageModifiedUserField',
      'name' => 'PageNameField',
      'next' => 'PageNextField',
      'numChildren' => 'PageNumChildrenField',
      'parent' => 'PageParentField',
      'parentId' => 'PageParentIdField',
      'parents' => 'PageParentsField',
      'path' => 'PagePathField',
      'prev' => 'PagePrevField',
      'rootParent' => 'PageRootParentField',
      'siblings' => 'PageSiblingsField',
      'url' => 'PageUrlField',
    ];
  }

}