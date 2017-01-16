<?php

namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\ProcessWire;

class Schema extends AbstractSchema {

  protected $fields = [];

  public function init()
  {
    foreach (wire('templates')->getAll() as $template) {
      $this->fields[] = new TemplateField($template);
    }
  }
  
  public function build(SchemaConfig $config)
  {
    $this->init();
    $config->getQuery()->addFields($this->fields);
  }

}