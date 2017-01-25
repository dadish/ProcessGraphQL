<?php namespace ProcessWire;

class GraphQLConfig extends Moduleconfig {

  public function getDefaults()
  {
    return array(
      'maxLimit' => 100,
      'debug' => false,
      'includeSystemTemplates' => false,
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
    $inputfields->add($f);

    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'debug');
    $f->label = 'Debug';
    $f->description = 'When you turn on debug mode some extra fields will be available. Like `dbQueryCount` etc.';
    $inputfields->add($f);

    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'includeSystemTemplates');
    $f->label = 'Include system templates';
    $f->description = 'Check this option if you want GraphQL module to add system templates like `admin`, `role`, `user`, `permissions` to the QuerySchema.';
    $inputfields->add($f);

    return $inputfields;
  }

}