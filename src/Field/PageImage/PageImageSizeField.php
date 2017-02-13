<?php

namespace ProcessWire\GraphQL\Field\PageImage;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\Scalar\IntType;
use ProcessWire\GraphQL\Type\Object\PageImageType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Field\PageImage\EmptyPageImage;

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
    $canCreate = Utils::moduleConfig()->legalEditFields->has($value->field);
    $width = isset($args['width']) ? $args['width'] : null;
    $height = isset($args['height']) ? $args['height'] : null;

    // if there neither width nor heigth is given then we return empty image
    if (!$width && !$height) return new EmptyPageImage();

    // we create the image if user have rights for it
    if ($canCreate) return $value->size($width, $height);

    // if user has no rights to create the image then she
    // might be asking for variation already created
    $options = [];
    if ($width) $options['width'] = $width;
    if ($height) $options['height'] = $height;
    $thumbs = $value->getVariations($options);

    // If we find the variation then return it
    // otherwise return empty
    if ($thumbs->count()) return $thumbs->first();
    return new EmptyPageImage();
  }
}
