<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use ProcessWire\GraphQL\Traits\TemplateAwareTrait;
use ProcessWire\GraphQL\Type\InterfaceType\PageType as PageInterfaceType;

class TemplatedPageType extends AbstractObjectType {

  use TemplateAwareTrait;

  public Static function normalizeName(string $name)
  {
    return str_replace('-', '_', $name);
  }

  public function getName()
  {
    return ucfirst(self::normalizeName($this->template->name)) . 'PageType';
  }

  public function getDescription()
  {
    $desc = $this->template->description;
    if ($desc) return $desc;
    return "PageType with template `" . $this->template->name . "`.";
  }

  public function build($config)
  {
    $config->applyInterface(new PageInterfaceType());
    foreach ($this->template->fields as $field) {
      $className = "\\ProcessWire\\GraphQL\\Field\\Page\\Fieldtype\\" . $field->type->className();
      if (!class_exists($className)) continue;
      $config->addField(new $className($field));
    }
  }

  public function getInterfaces()
  {
      return [new PageInterfaceType()];
  }

}