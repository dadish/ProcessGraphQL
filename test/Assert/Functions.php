<?php

namespace ProcessWire\GraphQL\Test\Assert;

use ProcessWire\GraphQL\Test\Constraint\SchemaFieldExists;

function schemaFieldExists()
{
  return new SchemaFieldExists;
}

function assertSchemaFieldExists(array $fieldPath, $message = '')
{
  assertThat($fieldPath, schemaFieldExists(), $message);
}

function assertSchemaFieldNotExists($fieldPath, $message = '')
{
  assertThat($fieldPath, logicalNot(schemaFieldExists()), $message);
}
