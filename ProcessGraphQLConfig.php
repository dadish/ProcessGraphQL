<?php namespace ProcessWire;

use \ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;
use \ProcessWire\GraphQL\Type\InterfaceType\PageFileInterfaceType;
use \ProcessWire\GraphQL\Settings;

require_once $this->config->paths->site . 'modules/ProcessGraphQL/vendor/autoload.php';

class ProcessGraphQLConfig extends Moduleconfig {

  public function getDefaults()
  {
    return array(
      'maxLimit' => 50,
      'debug' => false,
      'legalTemplates' => [],
      'legalFields' => [],
      'legalPageFields' => [
        'created',
        'modified',
        'url',
        'id',
        'name',
        'httpUrl',
        ],
      'legalPageFileFields' => [
        'url',
        'httpUrl',
        'description',
      ],
      'fullWidthGraphiql' => false,
    );
  }

	public static function isLegalTemplateName($name)
	{
		if (!$name) return false;
		if (preg_match('/^[_A-Za-z][-_0-9A-Za-z]*$/', $name) !== 1) return false; // the GraphQL naming requirement
		if (strpos($name, '__') === 0) return false; // the names with `__` prefix are reserved by GraphQL
		if (in_array($name, Settings::getReservedWords())) return false; // some words that used now and might be for future
    return true;
	}

  public function getInputFields()
  {
    $inputfields = parent::getInputFields();

    // maxLimit
    $f = $this->modules->get('InputfieldInteger');
    $f->attr('name', 'maxLimit');
    $f->label = 'Max Limit';
    $f->description = 'Set the maximum value for `limit` selector field.';
    $f->required = true;
    $f->columnWidth = 50;
    $inputfields->add($f);

    // GraphiQL full width
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'fullWidthGraphiQL');
    $f->label = 'Full width GraphiQL';
    $f->description = 'Check this if you want GraphiQL on the backend to stretch to full width.';
    $f->columnWidth = 50;
    $inputfields->add($f);

    // legalTemplates
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalTemplates');
    $f->label = 'Legal Templates';
    $f->description = 'The pages with the templates that you select below will be available via your GraphQL api.';
    $gotDisabledFields = false;
    foreach (\ProcessWire\wire('templates') as $template) {
      $attributes = [];
      if (!self::isLegalTemplateName($template->name)) {
        $attributes['disabled'] = true;
        $gotDisabledFields = true;
      }
      $label = $template->flags & Template::flagSystem ? "{$template->name} `(system)`" : $template->name;
      $f->addOption($template->name, $label, $attributes);
    }
    $notes = "Please be careful with what you are exposing to the public. Choosing templates marked as `system` can lead to security vulnerabilities.";
    if ($gotDisabledFields) {
      $notes .= PHP_EOL;
      $notes .= "The template is disabled if it's name is incompatible or reserved for ProcessGraphQL module.";
    }
    $f->notes = $notes;
    $inputfields->add($f);

    // legalFields
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalFields');
    $f->label = 'Legal Fields';
    $f->description = 'The fields that you select below will be available via your GraphQL api.';
    $f->notes = 'Please be careful with what you are exposing to the public. Choosing fields marked as `system` can to lead security vulnerabilities.';
    foreach (\ProcessWire\wire('fields')->find("name!=pass") as $field) {
      if ($field->type instanceof FieldtypeFieldsetOpen) continue;
      if ($field->type instanceof FieldtypeFieldsetClose) continue;
      if ($field->type instanceof FieldtypeFieldsetTabOpen) continue;
      $f->addOption($field->name, $field->flags & Field::flagSystem ? "{$field->name} `(system)`" : $field->name);
    }
    $inputfields->add($f);

    // legalPageFields
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalPageFields');
    $f->label = 'Legal Page Fields';
    $f->description = 'Choose which built in `Page` fields you wish to be available via GraphQL api.';
    $f->notes = 'Be careful with fields like `parents` & `children` that will allow user to construct deeply nested queries that might be very expensive for your server to fulfill.';
    foreach (PageInterfaceType::getPageFields() as $fieldName => $fieldClassName) {
      $f->addOption($fieldName);
    }
    $inputfields->add($f);

    // legalPageFileFields
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalPageFileFields');
    $f->label = 'Legal PageFile Fields';
    $f->description = 'Choose which built in `PageFile` fields you wish to be available via GraphQL api.';
    foreach (PageFileInterfaceType::getPageFileFields() as $fieldName => $fieldClassName) {
      $f->addOption($fieldName);
    }
    $inputfields->add($f);

    return $inputfields;
  }

}