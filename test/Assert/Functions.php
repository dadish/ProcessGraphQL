<?php

use ProcessWire\GraphQL\Test\Constraint\TypePathExists;
use PHPUnit\Framework\Assert;

function typePathExists()
{
  return new TypePathExists();
}

function assertTypePathExists(array $fieldPath, $message = "")
{
  Assert::assertThat($fieldPath, typePathExists(), $message);
}

function assertTypePathNotExists($fieldPath, $message = "")
{
  Assert::assertThat(
    $fieldPath,
    Assert::logicalNot(typePathExists()),
    $message
  );
}

function assertStringContainsString(
  string $needle,
  string $haystack,
  string $message = ""
): void {
  Assert::assertStringContainsString(...\func_get_args());
}
