<?php namespace ProcessWire\GraphQL\InputType;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Template;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\InputType\PageUpdateInputType;
use ProcessWire\GraphQL\InputType\PageCreateInputType;

class RepeaterUpdateInputType
{ 
  public static function &type(Template $template)
  {  
    $type =& Cache::type(PageUpdateInputType::getName($template), function () use ($template) {
      return new InputObjectType([
        'name' => PageUpdateInputType::getName($template),
        'description' => "UpdateInputType for pages with template `{$template->name}`.",
        'fields' => self::getFields($template),
      ]);
    });
   
    return $type;
  }

  public static function getFields(Template $template)
  {
    $fields = [];

    // we need to know the id of the repater that is being updated
    $fields[] = [
      'name' => 'id',
      'type' => Type::nonNull(Type::id()),
      'description' => 'The id of the repeater item that you want to update.',
    ];

    $fields = array_merge($fields, PageCreateInputType::getTemplateFields($template));

    return $fields;
  }
}
