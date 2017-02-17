<?php

namespace ProcessWire\GraphQL\Field\Mutation;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Exception\ValidationException;
use Youshido\GraphQL\Exception\ResolveException;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\NonNullType;

use ProcessWire\Template;
use ProcessWire\Page;
use ProcessWire\NullPage;
use ProcessWire\Field;
use ProcessWire\FieldtypePage;

use ProcessWire\GraphQL\Type\Object\TemplatedPageType;
use ProcessWire\GraphQL\Type\Input\TemplatedPage\UpdateInputType;

class UpdateTemplatedPage extends AbstractField {

  protected $template;

  public function __construct(Template $template)
  {
    $this->template = $template;
    parent::__construct([]);
  }

  public function getName()
  {
    $typeName = ucfirst(TemplatedPageType::normalizeName($this->template->name));
    return "update{$typeName}";
  }

  public function getType()
  {
    return new TemplatedPageType($this->template);
  }

  public function getDescription()
  {
    return "Allows you to update Pages with template `{$this->template->name}`.";
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => 'id',
      'type' => new NonNullType(new IntType()),
    ]));
    
    $config->addArgument(new InputField([
      'name' => 'page',
      'type' => new NonNullType(new UpdateInputType($this->template)),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    // prepare neccessary variables
    $pages = \ProcessWire\wire('pages');
    $sanitizer = \ProcessWire\wire('sanitizer');
    $fields = \ProcessWire\wire('fields');
    $values = (array) $args['page'];
    $id = (integer) $args['id'];
    $p = $pages->get($id);
    $p->of(false);

    /*********************************************\
     *                                           *
     * Don't ever take sides against the family! *
     *                                           *
    \*********************************************/
    if (isset($values['parent'])) {

      // find the parent
      $parentSelector = $values['parent'];
      $parent = $pages->find($sanitizer->selectorValue($parentSelector))->first();

      // if no parent then no good. No child should born without a parent!
      if (!$parent || $parent instanceof NullPage) throw new ValidationException("Could not find the `parent` page with `$parentSelector`.");

      // make sure user is allowed to add children to this parent
      $legalAddTemplates = Utils::moduleConfig()->legalAddTemplates;
      if (!$legalAddTemplates->has($parent->template)) throw new ValidationException("You are not allowed to add children to the parent: '$parentSelector'.");

      // make sure parent is allowed as a parent for this page
      $parentTemplates = $this->template->parentTemplates;
      if (count($parentTemplates) && !in_array($parent->template->id, $parentTemplates)) throw new ValidationException("`parent` is not allowed as a parent.");

      // make sure parent is allowed to have children
      if ($parent->template->noChildren === 1) throw new ValidationException("`parent` is not allowed to have children.");

      // make sure the page is allowed as a child for parent
      $childTemplates = $parent->template->childTemplates;
      if (count($childTemplates) && !in_array($this->template->id, $childTemplates)) throw new ValidationException("not allowed to be a child for `parent`.");
    }

    if (isset($values['name'])) {
      
      // check if the name is valid
      $name = $sanitizer->pageName($values['name']);
      if (!$name) throw new ValidationException('value for `name` field is invalid,');
      
      // find out if the name is taken
      $taken = $pages->find("parent=$parent, name=$name")->count();
      if ($taken) throw new ValidationException('`name` is already taken.');
    }

    // update the values from client
    foreach ($values as $fieldName => $value) {
      $field = $fields->get($fieldName);
      if (!$field instanceof Field) continue;
      if ($field->type->className() === 'FieldtypePage') $value = implode('|', $value);
      $p->$fieldName = $value;
    }

    // save the page to db
    if ($p->save()) return $pages->get("$p");

    // If we did not return till now then no good!
    throw new ResolveException("Could not create page `$name` with template `{$this->template->name}`");
  }

}
