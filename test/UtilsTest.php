<?php

namespace ProcessWire\GraphQL\Test;

use ProcessWire\Template;
use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class UtilsTest extends GraphQLTestCase
{
  public static function testIsRepeaterTemplate()
  {
    $repeaterTemplate = new Template();
    $repeaterTemplate->name = 'something';
    assertFalse(Utils::isRepeaterTemplate($repeaterTemplate), 'Incorrectly marks template as a repeater.');
    $repeaterTemplate->name = 'repeater_something';
    assertFalse(Utils::isRepeaterTemplate($repeaterTemplate), 'Incorrectly marks template as a repeater.');
    $repeaterTemplate->flags = $repeaterTemplate->flags | Template::flagSystem;
    assertTrue(Utils::isRepeaterTemplate($repeaterTemplate), 'Does not properly detect a repeater template.');
  }
}
