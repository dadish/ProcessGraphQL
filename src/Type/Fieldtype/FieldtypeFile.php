<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\Page;
use ProcessWire\Field;
use ProcessWire\GraphQL\Type\FileType;
use GraphQL\Type\Definition\Type;
use GraphQL\Deferred;
use ProcessWire\DatabaseQuerySelect;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\PagesBuffer;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\SetValueTrait;

class FieldtypeFile
{
  use InputFieldTrait;
  use SetValueTrait;

  private static $filesData = [];

  public static function type()
  {
    return Type::listOf(FileType::type());
  }

  public static function field(Field $field)
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
        'resolve' => function (Page $page) use ($field) {
          PagesBuffer::add($field->name, $page);
          return new Deferred(function () use ($page, $field){
            $ids = PagesBuffer::get($field->name);
            PagesBuffer::clear($field->name);
            if ($ids && count($ids)) {
              self::loadFilesData($ids, $field);
            }
            return self::getFieldValue($page, $field);
          });
        }
      ];
    });
  }

  public static function loadFilesData(array $pageIDs, Field $field) {

    if(!count($pageIDs)) {
      return;
    }

    $database = Utils::database();
    $fieldType = $field->type;
    $fieldName = $database->escapeCol($field->name);
    $schema = $fieldType->getDatabaseSchema($field);
    $table = $database->escapeTable($field->table);
    $stmt = null;

    $query = $fieldType->wire(new DatabaseQuerySelect());
    $query = $fieldType->getLoadQuery($field, $query);
    $query->select("$table.pages_id AS `{$fieldName}__pages_id`"); // QA
    $ids = implode(', ', $pageIDs);
    $query->where("$table.pages_id IN ($ids)");
    $query->from($table);

    try {
      $stmt = $query->prepare();
      $result = $database->execute($stmt);
    } catch(\Exception $e) {
      $result = false;
      $fieldType->trackException($e, false, true);
    }

    if(!$result) return null;

    $fieldName = $database->escapeCol($field->name);
    unset($schema['keys'], $schema['xtra'], $schema['sort']);
    $values = [];

    /** @noinspection PhpAssignmentInConditionInspection */
    while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
      $value = [];
      foreach($schema as $k => $unused) {
        $key = "{$fieldName}__{$k}";
        $value[$k] = $row[$key];
      }
      $pageID = $value['pages_id'];
      unset($value['pages_id']);
      if (!isset($values[$pageID])) {
        $values[$pageID] = [];
      }
      $values[$pageID][] = $value;
    }

    $stmt->closeCursor();

    self::$filesData[$field->name] = $values;
  }

  public static function getFieldValue(Page $page, Field $field)
  {
    $id = (string) $page->id;
    $key = $field->name;
    $filesData = self::$filesData[$key];
    $value = [];
    if (isset($filesData[$id])) {
      $value = $filesData[$id];
      $value = $field->type->_callHookMethod('wakeupValue', array($page, $field, $value));
      $value = $field->type->sanitizeValue($page, $field, $value);
    }
    return $value;
  }
}
