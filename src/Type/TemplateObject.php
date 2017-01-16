<?php namespace ProcessWire\GraphQL;

use ProcessWire\Template;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IdType;

class TemplateObjectType extends AbstractObjectType {

  protected $template;

  public function __construct(Template $template, array $config = [])
  {
    $this->template = $template;
    return parent::__construct($config);
  }

  public function build($config)
  {
    $config->addFields([
      'name' => new StringType(),
      'id' => new IdType(),
    ]);
  }

  public function getName()
  {
    return 'Template';
  }

}