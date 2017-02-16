<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\GraphQL\Type\Object\PageFileType;
use ProcessWire\GraphQL\Field\PageImage\PageImageSizeField;
use ProcessWire\GraphQL\Utils;
use Youshido\GraphQL\Field\FieldInterface;

class PageImageType extends PageFileType {

  public function getName()
  {
    return 'PageImage';
  }

  public function getDescription()
  {
    return 'ProcessWire PageImage.';
  }

  public function build($config)
  {
    parent::build($config);

    $fields = self::getPageImageFields();
    $legalPageImageFields = Utils::moduleConfig()->legalPageImageFields;
    foreach ($fields as $fieldName => $fieldConfig) {
      if (!in_array($fieldName, $legalPageImageFields)) continue;
      if ($fieldConfig instanceof FieldInterface) {
        $config->addField($fieldConfig);
        continue;
      }
      $config->addField($fieldName, $fieldConfig);
    }
  }

  public static function getPageImageFields()
  {
    return [
      'width' => [
        'type' => new IntType(),
        'description' => 'The width of the image.',
        'resolve' => function ($value) {
          return (integer) $value->width;
        }
      ],
      'height' => [
        'type' => new IntType(),
        'description' => 'The height of the image.',
        'resolve' => function ($value) {
          return (integer) $value->height;
        }
      ],
      'variations' => [
        'type' => new ListType(new PageImageType()),
        'description' => 'Returns all size variations of the image.',
        'resolve' => function ($value) {
          return $value->getVariations();
        }
      ],
      'size' => new PageImageSizeField(),
    ];
  }

}
