<?php namespace ProcessWire;

class GraphQLConfig extends Moduleconfig {

  public function getDefaults()
  {
    return array(
      'baseInterfaceName' => 'page',
    );
  }

  public function getInputFields()
  {
    $inputfields = parent::getInputFields();

    $f = $this->modules->get('InputfieldText');
    $f->attr('name', 'baseInterfaceName');
    $f->label = 'Base Interface Name';
    $f->required = true;
    $inputfields->add($f);

    return $inputfields;
  }

}