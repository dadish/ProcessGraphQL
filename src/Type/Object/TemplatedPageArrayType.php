<?php

namespace ProcessWire\GraphQL\Type\Object;

use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\GraphQL\Field\TemplatedPageArray\TemplatedPageArrayFindField;
use ProcessWire\GraphQL\Field\TemplatedPageArray\TemplatedPageArrayListField;
use ProcessWire\Template;

class TemplatedPageArrayType extends PageArrayType {

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
    return ucfirst(self::normalizeName($this->template->name)) . 'PageArray';
  }

  public function getDescription()
  {
    $desc = $this->template->description;
    if ($desc) return $desc;
    return "PageArray that stores only pages with template `" . $this->template->name . "`.";
  }

  public function build($config)
  {
    parent::build($config);
    $config->addField(new TemplatedPageArrayFindField($this->template));
    $config->addField(new TemplatedPageArrayListField($this->template));
  }

}