<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\ImageType;
use ProcessWire\GraphQL\Type\CacheTrait;
use GraphQL\Type\Definition\Type;

class FieldtypeImage
{
  use CacheTrait;
  public static function type()
  {
    return self::cache('dafault', function () {
      return Type::listOf(ImageType::type());
    });
    
  }

  public static function field($options)
  {
    return self::cache('field-' . $options['name'], function () use ($options) {
      return array_merge($options, [
        'type' => self::type(),
      ]);
    });
  }
}
