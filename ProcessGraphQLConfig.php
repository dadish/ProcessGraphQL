<?php namespace ProcessWire;

use \ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;

require_once $this->config->paths->site . 'modules/ProcessGraphQL/vendor/autoload.php';

class ProcessGraphQLConfig extends Moduleconfig {

  public function getDefaults()
  {
    return array(
      'maxLimit' => 100,
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
      'fullWidthGraphiql' => false,
    );
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
    $f->columnWidth = 35;
    $inputfields->add($f);

    // GraphiQL full width
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'fullWidthGraphiQL');
    $f->label = 'Full width GraphiQL';
    $f->description = 'Check this if you want GraphiQL on the backend to stretch to full width.';
    $f->columnWidth = 30;
    $inputfields->add($f);

    // legalTemplates
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalTemplates');
    $f->label = 'Legal Templates';
    $f->description = 'The pages with the templates that you select below will be available via your GraphQL api.';
    $f->notes = 'Please be careful with what you are exposing to the public. Choosing templates marked as `system` can lead to security vulnerabilities.';
        foreach (\ProcessWire\wire('templates') as $template) {
      $f->addOption($template->name, $template->flags & Template::flagSystem ? "{$template->name} `(system)`" : $template->name);
    }
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
    $f->description = 'Choose which built in page fields you wish to be available via GraphQL api.';
    $f->notes = 'Be careful with fields like `parents` & `children` that will allow user to construct deeply nested queries that might be very expensive for your server to fulfill.';
    foreach (PageInterfaceType::getPageFields() as $fieldName => $fieldClassName) {
      $f->addOption($fieldName);
    }
    $inputfields->add($f);

    return $inputfields;
  }

}