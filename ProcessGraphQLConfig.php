<?php namespace ProcessWire;

class ProcessGraphQLConfig extends Moduleconfig {

  public function getDefaults()
  {
    return array(
      'maxLimit' => 100,
      'debug' => false,
      'legalTemplates' => [],
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
    $f->columnWidth = 50;
    $inputfields->add($f);

    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'debug');
    $f->label = 'Debug';
    $f->description = 'When you turn on debug mode some extra fields will be available. Like `dbQueryCount` etc.';
    $f->columnWidth = 50;
    $inputfields->add($f);

    $f = $this->modules->get('InputfieldCheckboxes');
    foreach (\ProcessWire\wire('templates') as $template) {
      $f->addOption($template->name, $template->flags === 8 ? "{$template->name} (system)" : $template->name);
    }
    $f->optionColumns = 4;
    $f->attr('name', 'legalTemplates');
    $f->label = 'Legal Templates';
    $f->description = 'The pages with the templates that you select below will be available via your GraphQL api.';
    $f->notes = 'Please be careful with you are exposing to the public. Choosing templates marked as system can lead security issues.';
    $inputfields->add($f);

    return $inputfields;
  }

}