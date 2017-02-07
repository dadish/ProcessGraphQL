<?php

namespace ProcessWire\GraphQL\Type\InterfaceType;

use Youshido\GraphQL\Type\InterfaceType\AbstractInterfaceType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IntType;
use ProcessWire\Pageimage;
use ProcessWire\GraphQL\Type\Object\PageFileType;
use ProcessWire\GraphQL\Type\Object\PageImageType;
use ProcessWire\GraphQL\Settings;

class PageFileInterfaceType extends AbstractInterfaceType {

  public function getName()
  {
    return 'PageFileInterface';
  }

  public function getDescription()
  {
    return 'ProcessWire PageFile interface.';
  }

  public function build($config)
  {
    $fields = self::getPageFileFields();
    $legalPageFileFields = Settings::getLegalPageFileFields();
    foreach ($fields as $fieldName => $fieldConfig) {
      if (!in_array($fieldName, $legalPageFileFields)) continue;
      $config->addField($fieldName, $fieldConfig);
    }
  }

  public function resolveType($opject)
  {
    if ($object instanceof Pageimage) return new PageImageType();
    return new PageFileType();
  }

  public static function getPageFileFields()
  {
    return [
      'url' => [
        'type' => new StringType(),
        'description' => 'URL to the file on the server.',
        'resolve' => function ($value) {
          return (string) $value->url;
        }
      ],

      'httpUrl' => [
        'type' => new StringType(),
        'description' => 'The web accessible URL (with scheme and hostname) to this Pagefile.',
        'resolve' => function ($value) {
          return (string) $value->httpUrl();
        }
      ],

      'URL' => [
        'type' => new StringType(),
        'description' => "Same as 'url' property but with browser cache busting query
                          string appended that represents the file's modification time.",
        'resolve' => function ($value) {
          return (string) $value->URL;
        }
      ],

      'basename' => [
        'type' => new StringType(),
        'description' => 'The filename without the path.',
        'resolve' => function ($value) {
          return (string) $value->basename;
        }
      ],

      'description' => [
        'type' => new StringType(),
        'description' => 'The description of the file.',
        'resolve' => function ($value) {
          return (string) $value->description;
        }
      ],

      'ext' => [
        'type' => new StringType(),
        'description' => "File’s extension.",
        'resolve' => function ($value) {
          return (string) $value->ext;
        }
      ],

      'filesize' => [
        'type' => new IntType(),
        'description' => 'File size (number of bytes).',
        'resolve' => function ($value) {
          return (integer) $value->filesize;
        }
      ],

      'modified' => [
        'type' => new IntType(),
        'description' => 'Unix timestamp of when Pagefile (file, description or tags) was last modified.',
        'resolve' => function ($value) {
          return (integer) $value->modified;
        }
      ],

      'mtime' => [
        'type' => new IntType(),
        'description' => 'Unix timestamp of when file (only) was last modified.',
        'resolve' => function ($value) {
          return (integer) $value->mtime;
        }
      ],

      'created' => [
        'type' => new IntType(),
        'description' => 'Unix timestamp of when file was created.',
        'resolve' => function ($value) {
          return (integer) $value->created;
        }
      ],

      'filesizeStr' => [
        'type' => new StringType(),
        'description' => 'File size as a formatted string, i.e. “123 Kb”.',
        'resolve' => function ($value) {
          return (string) $value->filesizeStr;
        }
      ],
    ];
  }


}