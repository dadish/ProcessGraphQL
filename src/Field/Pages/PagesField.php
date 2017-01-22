<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;

use ProcessWire\GraphQL\Type\Object\PagesType;

class PagesField extends AbstractField {

  public function getType()
  {
    return new PagesType();
  }

  public function getName()
  {
    return 'pages';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return  \Processwire\wire('pages');
  }

}