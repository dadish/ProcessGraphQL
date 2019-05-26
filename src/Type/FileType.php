<?php

namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\CacheTrait;

class FileType {

  use CacheTrait;

  public static $name = 'File';

  public static $description = 'ProcessWire PageFile.';

  public static function buildType()
  {
    return new ObjectType([
      'name' => self::$name,
      'description' => self::$description,
      'fields' => function () {
        return self::getFields();
      }
    ]);
  }

  public static function getFields()
  {
    return [
      [
        'name' => 'url',
        'type' => Type::string(),
        'description' => 'URL to the file on the server.',
        'resolve' => function ($value) {
          return (string) $value->url;
        }
      ],

      [
        'name' => 'httpUrl',
        'type' => Type::string(),
        'description' => 'The web accessible URL (with scheme and hostname) to this Pagefile.',
        'resolve' => function ($value) {
          return (string) $value->httpUrl;
        }
      ],

      [
        'name' => 'URL',
        'type' => Type::string(),
        'description' => "Same as 'url' property but with browser cache busting query
                          string appended that represents the file's modification time.",
        'resolve' => function ($value) {
          return (string) $value->URL;
        }
      ],

      [
        'name' => 'basename',
        'type' => Type::string(),
        'description' => 'The filename without the path.',
        'resolve' => function ($value) {
          return (string) $value->basename;
        }
      ],

      [
        'name' => 'description',
        'type' => Type::string(),
        'description' => 'The description of the file.',
        'resolve' => function ($value) {
          return (string) $value->description;
        }
      ],

      [
        'name' => 'ext',
        'type' => Type::string(),
        'description' => "File’s extension.",
        'resolve' => function ($value) {
          return (string) $value->ext;
        }
      ],

      [
        'name' => 'filesize',
        'type' => Type::int(),
        'description' => 'File size (number of bytes).',
        'resolve' => function ($value) {
          return (integer) $value->filesize;
        }
      ],

      [
        'name' => 'modified',
        'type' => Type::int(),
        'description' => 'Unix timestamp of when Pagefile (file, description or tags) was last modified.',
        'resolve' => function ($value) {
          return (integer) $value->modified;
        }
      ],

      [
        'name' => 'mtime',
        'type' => Type::int(),
        'description' => 'Unix timestamp of when file (only) was last modified.',
        'resolve' => function ($value) {
          return (integer) $value->mtime;
        }
      ],

      [
        'name' => 'created',
        'type' => Type::int(),
        'description' => 'Unix timestamp of when file was created.',
        'resolve' => function ($value) {
          return (integer) $value->created;
        }
      ],

      [
        'name' => 'filesizeStr',
        'type' => Type::string(),
        'description' => 'File size as a formatted string, i.e. “123 Kb”.',
        'resolve' => function ($value) {
          return (string) $value->filesizeStr;
        }
      ],
    ];
  }
}
