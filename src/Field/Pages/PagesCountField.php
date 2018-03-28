<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Field\AbstractField;
use ProcessWire\GraphQL\Field\Traits\OptionalSelectorTrait;

class PagesCountField extends AbstractField {

  use OptionalSelectorTrait;

	public function getType()
	{
		return new IntType();
	}

	public function getName()
	{
		return 'count';
	}

  public function getDescription()
  {
    return 'Count and return how many pages will match the given selector or all pages of the site if no selector given.';
  }

}