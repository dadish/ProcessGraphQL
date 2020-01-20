<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Deferred;
use ProcessWire\Page;
use ProcessWire\PageArray;
use ProcessWire\FieldtypePage as PWFieldtypePage;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\Inputfield\InputfieldPage;
use ProcessWire\GraphQL\Type\PageArrayType;
use ProcessWire\GraphQL\Type\SelectorType;
use ProcessWire\GraphQL\PagesBuffer;

class FieldtypePage
{
  public static function type($field)
  {
    $template = null;
    // if template is chosen for the FieldtypePage
    // then we resolve to TemplatedPageArrayType
    if ($field->template_id) {
      $template = \ProcessWire\wire('templates')->get($field->template_id);
    }
      
    return PageArrayType::type($template);
  }

  public static function field($field)
  {
    return Cache::field($field->name, function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => self::type($field),
        'resolve' => function ($value, $args, $context, $info) use ($field) {
          $data = $value->getArray();
          if (isset($data[$field->name])) {
            $data = $data[$field->name];
            if (!empty($data)) {
              $pageIDs = PagesBuffer::add($field->name, $data);
              if (count($pageIDs)) {
                $value->data($field->name, $pageIDs);
              }
            }
          }
          return new Deferred(function () use ($value, $field, $info) {
            $finderOptions = PageArrayType::getFinderOptions($info);
            PagesBuffer::loadPages($field->name, $finderOptions);
            $fieldName = $field->name;
            $field->derefAsPage = PWFieldtypePage::derefAsPageArray;
            $value = $value->$fieldName;
            if (!$value instanceof PageArray) {
              return new PageArray();
            }
            return $value->find(SelectorType::parseValue(""));
          });
        }
      ];
    });
  }

  public static function inputField($field)
  {
    return InputfieldPage::inputField($field);
  }

  public static function setValue(Page $page, $field, $value)
  {
    return InputfieldPage::setValue($page, $field, $value);
  }
}
