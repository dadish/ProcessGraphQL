<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldTrait;

class FieldtypeThirdParty
{
  use FieldTrait;

  public static function &type($field)
  {
    $thirdPartyClassName = self::getThirdPartyClassName($field);
    $type =& Cache::type("type--{$field->name}", function () use ($field, $thirdPartyClassName) {
      return $thirdPartyClassName::getType($field);
    });
    return $type;
  }

  public static function &inputField($field)
  {
    $inputType =& Cache::field("input--{$field->name}", function () use ($field) {
      $fieldSettings = self::field($field);
      $thirdPartyClassName = self::getThirdPartyClassName($field);
      if (method_exists($thirdPartyClassName, 'getInputType')) {
        $fieldSettings['type'] = $thirdPartyClassName::getInputType($field);
      }
      return $fieldSettings;
    });
    return $inputType;
  }

  public static function setValue($page, $field, $value)
  {
    $thirdPartyClassName = self::getThirdPartyClassName($field);
    return $thirdPartyClassName::setValue($page, $field, $value);
  }

  public static function getThirdPartyClassName($field)
  {
    $thirdPartyClassName = "\\ProcessWire\\GraphQL" . $field->type->className();
    if (class_exists($thirdPartyClassName)) {
      return $thirdPartyClassName;
    } else {
      return null;
    }
  }
}
