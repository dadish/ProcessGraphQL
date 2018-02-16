<?php

namespace ProcessWire\GraphQL\Field\Traits;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\GraphQL\Utils;
use ProcessWire\NullPage;
use ProcessWire\FieldtypeDatetime;

trait DatetimeResolverTrait {

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => 'format',
      'type' => new StringType(),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->getName();
    
    if (isset($args['format'])) {
      $format = $args['format'];
      $rawValue = $value->$fieldName;
      if (Utils::fields()->get($fieldName) instanceof FieldtypeDatetime) {
        $rawValue = $value->getUnformatted($fieldName);
      }
      return date($format, $rawValue);
    }
    
    return $value->$fieldName;
  }
}