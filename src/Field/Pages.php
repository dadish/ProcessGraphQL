<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Execution\ResolveInfo;

use Youshido\GraphQL\Parser\Ast\TypedFragmentReference;

use \ChromePhp;

class PagesField extends AbstractField {

  public function gettype()
  {
    return new ListType(new PageUnionType());
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument('selector', new StringType());
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $pages = [];

    foreach ($info->getFieldASTList() as $field) {
      if ($field instanceOf TypedFragmentReference) {
        $pages[] = $field->getTypeName();
      }
    }

    ChromePhp::log($pages);
    return [];
  }

}