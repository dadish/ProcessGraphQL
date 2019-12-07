<?php

namespace ProcessWire\GraphQL\Field;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Utils;
use ProcessWire\Language as PWLanguage;

class Language {
  public static function field()
  {
    return [
      'type' => Type::string(),
      'name' => 'language',
      'description' => "Set the language of the content you are requesting. __Note__: Place this field on top of other fields whose language you want to set.",
      'args' => [
        'name' => Type::nonNull(Type::string())
      ],
      'resolve' => function ($pages, array $args)
      {
        $languageName = Utils::sanitizer()->pageName($args['name']);
        $language = Utils::languages()->get($languageName);
        if ($language instanceof PWLanguage) {
          Utils::user()->language = $language;
          return $language->name;
        } else {
          return 'Unknown language!';
        }   
      }
    ];
  }
}