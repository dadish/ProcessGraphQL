<?php

namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\FileType;
use ProcessWire\GraphQL\Type\EmptyImage;
use ProcessWire\GraphQL\Type\CacheTrait;

class ImageType {

  use CacheTrait;

  public static $name = 'Image';

  public static $description = 'ProcessWire PageImage.';

  public static function buildType()
  {
    $selfType = null;
    $selfType = new ObjectType([
      'name' => self::$name,
      'description' => self::$description,
      'fields' => function () use (&$selfType) {
        return self::getFields($selfType);
      }
    ]);
    return $selfType;
  }

  public static function getFields($selfType)
  {
    $fields = [
      [
        'name' => 'width',
        'type' => Type::int(),
        'description' => 'The width of the image.',
        'resolve' => function ($value) {
          return (integer) $value->width;
        }
      ],
      [
        'name' => 'height',
        'type' => Type::int(),
        'description' => 'The height of the image.',
        'resolve' => function ($value) {
          return (integer) $value->height;
        }
      ],
      [
        'name' => 'variations',
        'type' => Type::listOf($selfType),
        'description' => 'Returns all size variations of the image.',
        'resolve' => function ($value) {
          return $value->getVariations();
        }
      ],
      [
        'name' => 'size',
        'type' => $selfType,
        'description' => 'Create a thumbnail of the PageImage with the desired size.',
        'args' => [
          [
            'name' => 'width',
            'type' => Type::int(),
            'description' => 'Target width of the new image',
          ],
          [
            'name' => 'height',
            'type' => Type::int(),
            'description' => 'Target height of the new image',
          ],
        ],
        'resolve' => function($value, array $args) {
          $canCreate = Utils::hasFieldPermission('edit', $value->field, Utils::moduleConfig()->currentTemplateContext);
          $width = isset($args['width']) ? $args['width'] : null;
          $height = isset($args['height']) ? $args['height'] : null;
      
          // if there neither width nor heigth is given then we return empty image
          if (!$width && !$height) return new EmptyPageImage();
      
          // we create the image if user have rights for it
          if ($canCreate) return $value->size($width, $height);
      
          // if user has no rights to create the image then she
          // might be asking for variation already created
          $variations = $value->getVariations();
          foreach ($variations as $variation) {
            if ($width && $variation->width !== $width) continue;
            if ($height && $variation->height !== $height) continue;
            return $variation;
          }
          return new EmptyImage();
        }
      ]
    ];

    // add fields from FileType
    foreach (FileType::type()->getFields() as $field) {
      $fields[] = $field;
    }
 
    return $fields;
  }
}
