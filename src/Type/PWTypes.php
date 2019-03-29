<?php namespace ProcessWire\GraphQL\Type;

use ProcessWire\GraphQL\Type\SelectorType;
use ProcessWire\GraphQL\Type\UserType;

class PWTypes
{
  public const SELECTOR  = 'Selector';
  public const USER  = 'User';

  /** @var Type[] */
  private static $standardTypes;

  /**
   * @param string $name
   *
   * @return SelectorType|
   */
  private static function getStandardType($name = null)
  {
    if (self::$standardTypes === null) {
      self::$standardTypes = [
        self::SELECTOR => new SelectorType(),
        self::USER => UserType::create(),
      ];
    }

    return $name ? self::$standardTypes[$name] : self::$standardTypes;
  }

  /**
   * @return SelectorType
   *
   * @api
   */
  public static function selector()
  {
    return self::getStandardType(self::SELECTOR);
  }

  /**
   * @return UserType
   *
   * @api
   */
  public static function user()
  {
    return self::getStandardType(self::USER);
  }
}