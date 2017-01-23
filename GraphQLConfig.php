<?php namespace ProcessWire;

class GraphQLConfig extends Moduleconfig {

  public function getDefaults()
  {
    return array(
      'maxLimit' => 100,
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

    return $inputfields;
  }

}