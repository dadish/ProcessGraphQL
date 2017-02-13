<?php

namespace ProcessWire\GraphQL\Field\PageImage;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\Scalar\IntType;
use ProcessWire\GraphQL\Type\Object\PageImageType;
use Youshido\GraphQL\Execution\ResolveInfo;

class PageImageSizeField extends AbstractField{

  public function getType()
  {
    return new PageImageType();
  }

  public function getName()
  {
    return 'size';
  }

  public function getDescription()
  {
    return 'Create a thumbnail of the PageImage with the desired size.';
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument('width', [
      'type' => new IntType(),
      'description' => 'Target width of the new image',
    ]);
    $config->addArgument('height', [
      'type' => new IntType(),
      'description' => 'Target height of the new image',
    ]);
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $width = isset($args['width']) ? $args['width'] : null;
    $height = isset($args['height']) ? $args['height'] : null;
    return $value->size($width, $height);
  }
}
