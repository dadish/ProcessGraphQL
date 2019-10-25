<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserCreateNotAvailableParentTemplateChildTemplatesTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The template is legal.
   * + All the required fields are legal.
   * + The configured parent template is legal.
   * - But the configured parent template has childTemplates without target template id.
   */
  public static function getSettings()
  {
    $architectTemplate = Utils::templates()->get("architect");
    return [
      'login' => 'admin',
      'legalTemplates' => ['city', 'skyscraper'],
      'legalFields' => ['title'],
      'access' => [
        'templates' => [
          [
            'name' => 'city',
            'childTemplates' => [$architectTemplate->id],
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    $res = self::execute(GraphqlTestCase::introspectionQuery);
    $mutation = self::selectByProperty($res->data->__schema->types, 'name', 'Mutation');
    $this->assertNotNull($mutation, 'Mutation is available.');
    $createSkyscraper = self::selectByProperty($mutation->fields, 'name', 'createSkyscraper');
    $this->assertNull(
      $createSkyscraper,
      'Create field should not be available if configured parent template has childTemplates that does not match target template.'
    );
  }
}