<?php namespace ProcessWire\GraphQL\Type\Fieldtype\Traits;

trait InputFieldTrait
{
  public static function inputField($field)
  {
    return self::field($field);
  }
}
