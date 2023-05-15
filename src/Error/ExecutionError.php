<?php namespace ProcessWire\GraphQL\Error;

use GraphQL\Error\ClientAware;

class ExecutionError extends \Exception implements ClientAware
{
  public function isClientSafe(): bool
  {
    return true;
  }

  public function getCategory()
  {
    return 'execution';
  }
}
