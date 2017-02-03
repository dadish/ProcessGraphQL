<?php namespace ProcessWire;

class ProcessGraphQLConfig extends Moduleconfig {

  public function getDefaults()
  {
    return array(
      'maxLimit' => 100,
      'debug' => false,
      'legalTemplates' => [],
      'legalFields' => [],
      'fullWidthGraphiql' => false,
    );
  }

  public function getInputFields()
  {
    $inputfields = parent::getInputFields();

    $f = $this->modules->get('InputfieldInteger');
    $f->attr('name', 'maxLimit');
    $f->label = 'Max Limit';
    $f->description = 'Set the maximum value for `limit` selector field.';
    $f->required = true;
    $f->columnWidth = 35;
    $inputfields->add($f);

    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'debug');
    $f->label = 'Debug';
    $f->description = 'When you turn on debug mode some extra fields will be available. Like `dbQueryCount` etc.';
    $f->columnWidth = 35;
    $inputfields->add($f);

    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'fullWidthGraphiQL');
    $f->label = 'Full width GraphiQL';
    $f->description = 'Check this if you want GraphiQL on the backend to stretch to full width.';
    $f->columnWidth = 30;
    $inputfields->add($f);

    $f = $this->modules->get('InputfieldCheckboxes');
    foreach (\ProcessWire\wire('templates') as $template) {
      $f->addOption($template->name, $template->flags & Template::flagSystem ? "{$template->name} (system)" : $template->name);
    }
    $f->optionColumns = 4;
    $f->attr('name', 'legalTemplates');
    $f->label = 'Legal Templates';
    $f->description = 'The pages with the templates that you select below will be available via your GraphQL api.';
    $f->notes = 'Please be careful with what you are exposing to the public. Choosing templates marked as system can lead to security issues.';
    $inputfields->add($f);

    $f = $this->modules->get('InputfieldCheckboxes');
    foreach (\ProcessWire\wire('fields')->find("name!=pass") as $field) {
      if ($field->type instanceof FieldtypeFieldsetOpen) continue;
      if ($field->type instanceof FieldtypeFieldsetClose) continue;
      if ($field->type instanceof FieldtypeFieldsetTabOpen) continue;
      $f->addOption($field->name, $field->flags & Field::flagSystem ? "{$field->name} (system)" : $field->name);
    }
    $f->optionColumns = 4;
    $f->attr('name', 'legalFields');
    $f->label = 'Legal Fields';
    $f->description = 'The fields that you select below will be available via your GraphQL api.';
    $f->notes = 'Please be careful with what you are exposing to the public. Choosing fields marked as system can to lead security issues.';
    $inputfields->add($f);

    return $inputfields;
  }

}