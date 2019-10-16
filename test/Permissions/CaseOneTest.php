<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Permissions;
use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PermissionsCaseOneTest extends GraphQLTestCase
{
  const accessRules = [
    'legalTemplates' => ['skyscraper', 'city', 'architect'],
    'legalFields' => ['title', 'architect', 'height', 'floors', 'body'],
  ];

  use AccessTrait;

  public function testGetLegalTemplateIds() {
    $legalTemplateIds = Permissions::getLegalTemplateIds();
    $this->assertCount(3, $legalTemplateIds);
    $this->assertIsInt($legalTemplateIds[0]);
  }
}
