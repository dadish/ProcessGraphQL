<?php

namespace ProcessWire\GraphQL\Type\Input;

use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;;
use ProcessWire\Template;
use ProcessWire\GraphQL\Utils;

class TemplatedPageInputType extends AbstractInputObjectType {

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
    return ucfirst(self::normalizeName($this->template->name)) . 'PageInputType';
  }

  public function getDescription()
  {
    return "InputType for pages with template {$this->template->name}.";
  }

  public function build($config)
  {
    // parent
    $config->addField('parent', [
      'type' => new NonNullType(new StringType()),
      'description' => 'Id or the path of the parent page.',
    ]);

    // name
    $config->addField('name', [
      'type' => new NonNullType(new StringType()),
      'description' => 'ProcessWire page name.',
    ]);

    $unsupportedFieldtypes = [
      'FieldtypeFile',
      'FieldtypeImage',
    ];

    $legalFieldsName = Utils::moduleConfig()->legalFields->implode('|', 'name');
    // the template fields
    foreach ($this->template->fields->find("name=$legalFieldsName") as $field) {
      $className = $field->type->className();
      if (in_array($className, $unsupportedFieldtypes)) continue;
      $Class = "\\ProcessWire\\GraphQL\\Field\\Page\\Fieldtype\\" . $className;
      if (!class_exists($Class)) continue;
      $field = new $Class($field);
      $config->addField($field->getName(), [
        'type' => $field->getInputfieldType(),
        'description' => $field->getDescription(),
      ]);
    }
  }

}
