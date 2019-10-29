<?php

namespace ProcessWire\GraphQL\Test\Assert;

use ProcessWire\GraphQL\Test\Constraint\SchemaFieldExists;
use PHPUnit\Framework\Assert;

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

function assertStringContainsString(string $needle, string $haystack, string $message = ''): void
{
  Assert::assertStringContainsString(...\func_get_args());
}
