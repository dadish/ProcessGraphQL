<?php

namespace ProcessWire\GraphQL\Type\Scalar;

use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\Selectors;
use ProcessWire\SelectorEqual;
use ProcessWire\GraphQL\Settings;

class SelectorType extends StringType {

  const ARGUMENT_NAME = 's';

  public function getName()
  {
    return 'Selector';
  }

  public function getDescription()
  {
    return 'A ProcessWire selector.';
  }

  public function serialize($selectors)
  {
    $selectors = new Selectors($selectors);

    // make sure the limit field is not greater than max allowed
    $maxLimit = Settings::module()->maxLimit;
    $limitSelector = self::findSelectorByField($selectors, 'limit');
    if ($limitSelector) {
      if ($maxLimit < $limitSelector->value) $limitSelector->set('value', $maxLimit);
    } else {
      $limitSelector = new SelectorEqual('limit', $maxLimit);
      $selectors->add($limitSelector);
    }

    // return normalized selectors
    return $selectors;
  }

  public static function findSelectorByField(Selectors $selectors, string $target)
  {
    foreach ($selectors as $selector) {
      foreach ($selector->fields as $field) {
        if ($field === $target) return $selector;
      }
    }
    return null;
  }
  
}