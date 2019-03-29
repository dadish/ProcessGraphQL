<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Field\AbstractField;
use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\GraphQL\Field\Traits\OptionalSelectorTrait;

class PagesFindField extends AbstractField {

  use OptionalSelectorTrait;

  public function getType()
  {
    return new PageArrayType();
  }

	public function getName()
	{
		return 'find';
	}

  public function getDescription()
  {
    return 'Allows to search for all pages in the ProcessWire app.';
  }

}