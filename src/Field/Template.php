<?php namespace ProcessWire\GraphQL;

use ProcessWire\Template;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;

class TemplateField extends AbstractField {

  protected $type;

  protected $template;

  public function __construct(Template $template, array $config = [])
  {
    $this->template = $template;
    $this->type = new TemplateObjectType($this->template);
    return parent::__construct($config);
  }

  public function getType()
  {
    return $this->type;
  }

  public function getName()
  {
    return str_replace('-', '_', $this->template->name);
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return [
      'name' => $this->template->name,
      'id' => $this->template->id
    ];
  }
}