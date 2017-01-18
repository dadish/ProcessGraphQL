<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Type\InterfaceType\AbstractInterfaceType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\NonNullType;

class BasePageInterface extends AbstractInterfaceType {
  
  /**
   * 
   * 
   */
  public function build($config)
  {
    $config->addField('name', new NonNullType(new StringType()));
    $config->addField('id', new NonNullType(new IdType()));
    $config->addField('path', new NonNullType(new StringType()));
    $config->addField('url', new NonNullType(new StringType()));
    $config->addField('httpUrl', new NonNullType(new StringType()));
    $config->addField('parentID', new NonNullType(new IdType()));
    $config->addField('parents', new NonNullType(new FieldTypePage()));
    $config->addField('rootParent', new NonNullType(new IdType()));
    $config->addField('template', new NonNullType(new IdType()));
    $config->addField('numChildren', new NonNullType(new IntType()));
    $config->addField('children', new NonNullType(new FieldTypePage()));
    $config->addField('siblings', new NonNullType(new FieldTypePage()));
    $config->addField('next', new IdType());
    $config->addField('prev', new IdType());
    $config->addField('created', new NonNullType(new IntType()));
    $config->addField('modified', new NonNullType(new IntType()));
    $config->addField('createdUser', new NonNullType(new IdType()));
    $config->addField('modifiedUser', new NonNullType(new IdType()));
  }

  public function resolveType($page)
  {
    $template_name = $page['template'];
    $template = wire('templates')->get("name=$template_name");
    return new PageObjectType($template);
  }

  public function getName()
  {
    return 'basePage';
  }

}