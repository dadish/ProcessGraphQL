<?php

namespace ProcessWire\GraphQL\Type;

use ProcessWire\WireData;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\FileType;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Permissions;

class EmptyImage extends WireData {

  public function getVariations()
  {
    return new WireArray();
  }

}

class ImageType {

  public static $name = 'Image';

  public static $description = 'ProcessWire PageImage.';

  public static function &type()
  {
    $type =& Cache::type(self::$name, function () {
      return new ObjectType([
        'name' => self::$name,
        'description' => self::$description,
        'fields' => function () {
          return array_merge(self::getLegalBuiltInFields(), FileType::getLegalBuiltInFields());
        }
      ]);
    });
    return $type;
  }

  public static function getBuiltInFields()
  {
    $type =& self::type();
    return [
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
        'type' => Type::listOf($type),
        'description' => 'Returns all size variations of the image.',
        'resolve' => function ($value) {
          return $value->getVariations();
        }
      ],
      [
        'name' => 'size',
        'type' => $type,
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
          $canCreate = Permissions::canEditField($value->field, $value->page->template);
          $width = isset($args['width']) ? $args['width'] : null;
          $height = isset($args['height']) ? $args['height'] : null;
      
          // if there neither width nor heigth is given then we return empty image
          if (!$width && !$height) return new EmptyImage();
      
          // we create the image if user have rights for it
          if ($canCreate) return $value->size($width, $height);
      
          // if user has no rights to create the image then she
          // might be asking for variation already created
          $variations = $value->getVariations();
          foreach ($variations as $variation) {
            if ($width && $variation->width !== $width) {
              continue;
            }
            
            if ($height && $variation->height !== $height) {
              continue;
            }
            
            return $variation;
          }
          return new EmptyImage();
        }
      ]
    ];
  }
  public static function getLegalBuiltInFields()
  {
    return array_filter(self::getBuiltInFields(), function ($field) {
      return in_array($field['name'], Utils::module()->legalPageImageFields);
    });
  }
}
