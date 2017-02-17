<?php

namespace ProcessWire\GraphQL\Type\Input\TemplatedPage;

use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;;
use ProcessWire\Template;
use ProcessWire\GraphQL\Utils;

class CreateInputType extends AbstractInputObjectType {

  protected $template;

  /**
   * Construct the TemplatedPageInputType.
   * @param Template $template The ProcessWire template that is used to generate
   * this's type's fields.
   */
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
    return ucfirst(self::normalizeName($this->template->name)) . 'CreateInputType';
  }

  public function getDescription()
  {
    return "CreateInputType for pages with template {$this->template->name}.";
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
    
    // the list of input fields we do not 
    // for now
    $unsupportedFieldtypes = [
      'FieldtypeFile',
      'FieldtypeImage',
    ];

    $legalFieldsName = Utils::moduleConfig()->legalFields->implode('|', 'name');
    foreach ($this->template->fields->find("name=$legalFieldsName") as $field) {

      // get the field's GraphQL input class
      $className = $field->type->className();
      if (in_array($className, $unsupportedFieldtypes)) continue;
      $Class = "\\ProcessWire\\GraphQL\\Field\\Page\\Fieldtype\\" . $className;

      // if we do not have a GraphQL class for a field
      // it means we do not support it.
      if (!class_exists($Class)) continue;

      $f = new $Class($field);
      $config->addField($f->getName(), [
        'type' => $f->getInputfieldType(),
        'description' => $f->getDescription(),
      ]);
    }
  }

}
