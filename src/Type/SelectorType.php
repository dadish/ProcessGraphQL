<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Error\Error;
use ProcessWire\Template;
use ProcessWire\Selectors;
use ProcessWire\Selector;
use ProcessWire\SelectorEqual;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Cache;
use GraphQL\Type\Definition\CustomScalarType;

class SelectorType
{
  public static $name = 'Selector';

  public static $description = 'ProcessWire selector.';

  private static $parsedValues = [];

  public static function type()
  {
    return Cache::type(self::$name, function () {
      return new CustomScalarType([
        'name' => self::$name,
        'description' => self::$description,
        'serialize' => function ($value) {
          return (string) $value;
        },
        'parseValue' => function ($value) {
          return (string) $value;
        },
        'parseLiteral' => function ($valueNode) {
          return (string) $valueNode->value;
        }
      ]);
    });
  }

  /**
   * Parses an externally provided value (query variable) to use as an input
   *
   * @param mixed $value
   * @return string
   * @throws Error
   */
  public static function parseValue(string $value)
  {
    // if we already parsed the given selector
    // then return the cached result.
    $key = self::getCacheKeyForSelector($value);
    if (isset(self::$parsedValues[$key])) {
      return self::$parsedValues[$key];
    }


    if (!is_string($value)) {
      throw new Error(self::$name . " should be a string.");
    }

    $selectors = new Selectors($value);

    // make sure the limit field is not greater than max allowed
    $maxLimit = Utils::moduleConfig()->maxLimit;
    $limitSelector = self::findSelectorByField($selectors, 'limit');
    if ($limitSelector instanceof Selector) {
      if ($maxLimit < $limitSelector->value) $limitSelector->set('value', $maxLimit);
    } else {
      $limitSelector = new SelectorEqual('limit', $maxLimit);
      $selectors->add($limitSelector);
    }

    // make sure to limit the search to legal templates only
    $templateSelector = self::findSelectorByField($selectors, 'template');
    $legalTemplates = Utils::moduleConfig()->legalViewTemplates;
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

    // return selector as string
    self::$parsedValues[$key] = (string) $selectors;
    return self::$parsedValues[$key];
  }

  public static function findSelectorByField(Selectors $selectors, $target)
  {
    foreach ($selectors as $selector) {
      foreach ($selector->fields as $field) {
        if ($field === $target) return $selector;
      }
    }
    return null;
  }

  public static function getCacheKeyForSelector(string $selector)
  {
    $key = $selector;
    $seperator = '-';

    // max limit
    $key .= "$seperator$seperator";
    $key .= Utils::moduleConfig()->maxLimit;

    // legal templates
    $key .= "$seperator$seperator";
    $key .= Utils::moduleConfig()->legalViewTemplates->implode('-', 'name');

    return $key;
  }
}