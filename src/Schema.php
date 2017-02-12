<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Field\Pages\PagesField;
use ProcessWire\GraphQL\Field\TemplatedPageArray\TemplatedPageArrayField;
use ProcessWire\GraphQL\Field\Debug\DbQueryCountField;
use ProcessWire\GraphQL\Field\Auth\LoginField;
use ProcessWire\GraphQL\Field\Auth\LogoutField;
use ProcessWire\GraphQL\Field\User\UserField;
use ProcessWire\GraphQL\Field\Mutation\CreateTemplatedPage;
use ProcessWire\GraphQL\Field\LanguageField;

class Schema extends AbstractSchema {

  protected $fields = [];

  public function build(SchemaConfig $config)
  {
    /**
     * Query
     */
    $query = $config->getQuery();

    // $pages API
    $query->addField(new PagesField());

    // $templates
    foreach (Utils::moduleConfig()->legalTemplates as $template) {
      $query->addField(new TemplatedPageArrayField($template));
    }

    // Debugging
    if (\ProcessWire\Wire('config')->debug) {
      $query->addField(new DbQueryCountField());
    }

    // Auth
    $query->addfield(new LoginField());
    $query->addfield(new LogoutField());

    // User
    $query->addField(new UserField());

    // Language support
    if (Utils::moduleConfig()->languageEnabled) {
      $query->addField(new LanguageField());
    }

    /**
     * Mutation
     */
    $mutation = $config->getMutation();

    // CreatePage
    foreach (Utils::moduleConfig()->legalTemplates as $template) {
      $mutation->addField(new CreateTemplatedPage($template));
    }

  }

  public function getName()
  {
    return 'Root';
  }

}
