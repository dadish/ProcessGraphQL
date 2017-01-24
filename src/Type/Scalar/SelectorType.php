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

    // make sure to limit the search to templates that the 
    // user has page-view permission to
    $user = \ProcessWire\wire('user');
    if (!$user->isSuperuser()) {
      $templateSelector = self::findSelectorByField($selectors, 'template');
      $templates = \ProcessWire\wire('templates');
      $names = [];
      
      if ($templateSelector instanceof Selector) {
        // the user did specify `template` field in selector string
        foreach ($templateSelector->values as $templateName) {
          $template = $templates->get($templateName);
          if ($template instanceof Template && $user->hasPermission('page-view', $template)) {
            $names[] = $template->name;
          }
        }

        if (count($names)) {
          // at least one of the templates the user chose is viewable by her.
          $templateSelector->set('value', $names);
        } else {
          // none of the templates the user chose is viewable by her.
          // This means the result of the search should be empty PageArrayType;
          $selectors = new Selectors("name=''");
        }

      } else {
        // the user did not specify the template field in selector string.
        // In this case we add template field and set all the templates
        // viewable by her as the value
        $names = [];
        foreach ($templates->getAll() as $template) {
          if ($user->hasPermission('page-view', $template)) $names[] = $template->name;
        }
        $templateSelector = new SelectorEqual('template', $names);
        $selectors->add($templateSelector);
      }
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