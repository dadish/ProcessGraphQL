<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\Page;
use ProcessWire\Field;
use GraphQL\Type\Definition\Type;
use GraphQL\Deferred;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\PagesBuffer;
use ProcessWire\GraphQL\Type\ImageType;
use ProcessWire\GraphQL\Type\Fieldtype\FieldtypeFile;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\SetValueTrait;

class FieldtypeImage
{
  use InputFieldTrait;
  use SetValueTrait;

  public static function type()
  {
    return Type::listOf(ImageType::type());
  }

  public static function field(Field $field)
  {
    return Cache::field($field->name, function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => self::type($field),
        'resolve' => function (Page $page) use ($field) {
          PagesBuffer::add($field->name, $page);
          return new Deferred(function () use ($page, $field){
            $ids = PagesBuffer::get($field->name);
            PagesBuffer::clear($field->name);
            if ($ids && count($ids)) {
              FieldtypeFile::loadFilesData($ids, $field);
            }
            return FieldtypeFile::getFieldValue($page, $field);
          });
        }
      ];
    });
  }
}
