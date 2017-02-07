<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use ProcessWire\Template;
use ProcessWire\Field;
use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;
use ProcessWire\GraphQL\Settings;

class TemplatedPageType extends AbstractObjectType {

  protected $template;

  public function __construct(Template $template)
  {
    $this->template = $template;
    parent::__construct([]);
  }

  public Static function normalizeName($name)
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
    $legalFields = Settings::getLegalFields();
    $config->applyInterface(new PageInterfaceType());
    foreach ($this->template->fields as $field) {
      if (!$legalFields->has($field)) continue;
      if ($field->flags & Field::flagGlobal) continue; // global fields are already added via PageTypeInterface
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