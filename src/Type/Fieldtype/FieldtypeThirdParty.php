<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;

class FieldtypeThirdParty
{
  use CacheTrait;
  use FieldTrait;

  public static function &type($field)
  {
    $type =& self::cache($field->name, function () use ($field) {
      $thirdPartyClassName = self::getThirdPartyClassName($field);
      return $thirdPartyClassName::getType($field);
    });
    return $type;
  }

  public static function &inputField($field)
  {
    $inputType =& self::cache("input-type-{$field->name}", function () use ($field) {
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
