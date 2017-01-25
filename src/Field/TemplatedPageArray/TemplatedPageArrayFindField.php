<?php

namespace ProcessWire\GraphQL\Field\TemplatedPageArray;

use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use ProcessWire\GraphQL\Traits\TemplateAwareTrait;
use ProcessWire\GraphQL\Field\PageArray\PageArrayFindField;
use ProcessWire\GraphQL\Type\Scalar\TemplatedSelectorType;
use ProcessWire\GraphQL\Type\Object\TemplatedPagearrayType;

class TemplatedPageArrayFindField extends PageArrayFindField {

  use TemplateAwareTrait;

  public function getType()
  {
    return new TemplatedPageArrayType($this->template);
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => TemplatedSelectorType::ARGUMENT_NAME,
      'type' => new NonNullType(new TemplatedSelectorType($this->template)),
    ]));
  }

}