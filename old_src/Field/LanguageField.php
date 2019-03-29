<?php

namespace ProcessWire\GraphQL\Field;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\Language;
use ProcessWire\GraphQL\Utils;

class LanguageField extends AbstractField {

  public function getName()
  {
    return 'language';
  }

  public function getType()
  {
    return new StringType();
  }

  public function getDescription()
  {
    return "Set the language of the content you are requesting. __Note__: Place this field on top of other fields whose language you want to set.";
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument('name', new NonNullType(new StringType()));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $language = Utils::languages()->get($args['name']);
    if ($language instanceof Language) {
      Utils::user()->language = $language;
      return $language->name;
    } else {
      return 'Unknown language!';
    }
  }

}
