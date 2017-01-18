<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Type\Union\AbstractUnionType;

class PageUnionType extends AbstractUnionType {

  public function getTypes()
  {
    $types = [];
    foreach (wire('templates')->getAll() as $template) {
      $types[] = new PageObjectType($template);
    }
    return $types;
  }

  public function resolveType($page)
  {
    $template_name = $page['template'];
    $template = wire('templates')->get("name=$template_name");
    return new PageObjectType($template);
  }

}