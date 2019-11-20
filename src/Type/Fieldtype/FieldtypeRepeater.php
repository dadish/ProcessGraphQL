<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\Page;
use ProcessWire\Selectors;
use ProcessWire\GraphQL\Type\Inputfield\InputfieldRepeater;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\PageArrayType;
use ProcessWire\GraphQL\Type\SelectorType;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\SetValueTrait;

class FieldtypeRepeater
{
  use InputFieldTrait;
  use SetValueTrait;

  public static $name = 'Repeater';

  public static $description = 'Maintains a collection of fields that are repeated for any number of times.';

  public static function type($field)
  {
    $templateId = $field->get('template_id');
    $template = Utils::templates()->get($templateId);
    return PageArrayType::type($template);
  }

  public static function &field($field)
  {
    $field =& Cache::field($field->name, function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => self::type($field),
        'args' => [
          's' => [
            'type' => SelectorType::type(),
            'description' => "ProcessWire selector."
          ],
        ],
        'resolve' => function (Page $page, array $args) use ($field) {
          $fieldName = $field->name;
          $selector = "";
          if (isset($args['s'])) {
            $selector = $args['s'];
          }
          $selector = new Selectors($selector);
          $result = $page->$fieldName->find((string) $selector);
          if ($result instanceof NullPage) return null;
          return $result;
        }
      ];
    });

    return $field;
  }

  public static function inputField($field)
  {
    return InputfieldRepeater::inputField($field);
  }

  public static function setValue(Page $page, $field, $value)
  {
    return InputfieldRepeater::setValue($page, $field, $value);
  }
}
