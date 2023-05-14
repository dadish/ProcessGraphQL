<?php namespace ProcessWire\GraphQL\Error;

use GraphQL\Error\ClientAware;

class ValidationError extends \Exception implements ClientAware
{
  public function isClientSafe(): bool
  {
    return true;
  }

  public function getCategory()
  {
    return 'validation';
  }
}
