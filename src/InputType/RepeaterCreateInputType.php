<?php namespace ProcessWire\GraphQL\InputType;

use GraphQL\Type\Definition\InputObjectType;
use ProcessWire\Template;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\InputType\PageCreateInputType;

class RepeaterCreateInputType
{ 
  public static function &type(Template $template)
  {  
    $type =& Cache::type(PageCreateInputType::getName($template), function () use ($template) {
      return new InputObjectType([
        'name' => PageCreateInputType::getName($template),
        'description' => "CreateInputType for pages with template `{$template->name}`.",
        'fields' => self::getFields($template),
      ]);
    });
   
    return $type;
  }

  public static function getFields(Template $template)
  {
    $fields = [];

    // add template fields
    $fields = array_merge($fields, PageCreateInputType::getTemplateFields($template));
    
    // mark required fields as nonNull
    PageCreateInputType::markRequiredTemplateFields($fields, $template);

    return $fields;
  }
}
