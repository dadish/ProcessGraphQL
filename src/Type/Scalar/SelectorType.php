<?php

namespace ProcessWire\GraphQL\Type\Scalar;

use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\Template;
use ProcessWire\Selectors;
use ProcessWire\Selector;
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

    // make sure to limit the search to legal templates only
    $templateSelector = self::findSelectorByField($selectors, 'template');
    $legalTemplates = Settings::getLegalTemplates();
    $names = [];
    
    if ($templateSelector instanceof Selector) {
      // the user did specify `template` field in selector string
      // filter out templates that are not legal
      foreach ($templateSelector->values as $templateName) {
        $template = $legalTemplates->get($templateName);
        if ($template instanceof Template) $names[] = $template->name;
      }

      if (count($names)) {
        // at least one of the templates the user chose is legal
        // let the user search those
        $templateSelector->set('value', $names);
      } else {
        // none of the templates the user chose is viewable by her.
        // This means the result of the search should be empty PageArrayType;
        $selectors = new Selectors("name=''");
      }

    } else {
      // The user did not specify the template field in selector string.
      // That means she wants to see all the matching pages, but we add
      // template field and set all the legal templates to narrow the
      // search only to viewable pages
      $templateSelector = new SelectorEqual('template', $legalTemplates->explode('name'));
      $selectors->add($templateSelector);
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