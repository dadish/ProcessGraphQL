<?php namespace ProcessWire\GraphQL;

use ProcessWire\Template;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IdType;

class PageObjectType extends AbstractObjectType {

  protected $template;

  public function __construct(Template $template, array $config = [])
  {
    $this->template = $template;
    return parent::__construct($config);
  }

  public function build($config)
  {
    $config->applyInterface(new BasePageInterface());
    foreach ($this->template->fields as $field) {
      if ($config->hasField($field->name)) continue;
      $Class = 'ProcessWire\GraphQL\\' . $field->type->className();
      $config->addField($field->name, new $Class);
    }
  }

  public function getName()
  {
    return str_replace('-', '_', $this->template->name);
  }

  public function getInterfaces()
  {
    return [ new BasePageInterface() ];
  }

}