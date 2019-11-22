<?php

namespace ProcessWire\GraphQL;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\SelectorType;
use ProcessWire\Page;

class PagesBuffer
{
  private static $pageIDs = [];

  public static function loadPages($group, $options)
  {
    if (!isset(self::$pageIDs[$group]) || !count(self::$pageIDs[$group])) {
      // nothing to load if there are no page ids
      return;
    }
    $maxLimit = Utils::module()->maxLimit;
    Utils::module()->maxLimit = 100000;
    $selector = SelectorType::parseValue("");
    Utils::module()->maxLimit = $maxLimit;
    $selector .= ', id=' . implode('|', self::$pageIDs[$group]);
    Utils::pages()->find($selector, $options);
    self::clear($group);
  }

  public static function clear($group)
  {
    self::$pageIDs[$group] = [];
  }

  public static function add($group, $_ids)
  {
    if(is_string($_ids)) {
      // convert string of IDs to array
			if(strpos($_ids, '|') !== false) $_ids = explode('|', $_ids);
			else $_ids = explode(",", $_ids);
		} else if(is_int($_ids) || is_object($_ids)) {
			$_ids = array($_ids);
		}

    // sanitize ids
    $ids = [];
    foreach ($_ids as $id) {
      if (is_string($id)) {
        $id = (int) $id;
      } else if (is_object($id)) {
        $id = $id->id;
      }
      $ids[] = (int) $id;
    }

    if (!isset(self::$pageIDs[$group])) {
      self::$pageIDs[$group] = [];
    }
    self::$pageIDs[$group] = array_merge(self::$pageIDs[$group], $ids);
  }

  public static function get($group)
  {
    return self::$pageIDs[$group];
  }
}
