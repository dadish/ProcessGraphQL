<?php namespace ProcessWire\GraphQL\Type\Traits;

trait InputFieldTrait
{
  public static function inputField($field)
  {
    return self::field($field);
  }
}
