<?php namespace ProcessWire;

use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;
use ProcessWire\GraphQL\Type\InterfaceType\PageFileInterfaceType;
use ProcessWire\GraphQL\Utils;

require_once $this->config->paths->site . 'modules/ProcessGraphQL/vendor/autoload.php';

class ProcessGraphQLConfig extends Moduleconfig {

  public function getDefaults()
  {
    return array(
      /**
       * Sets the max for ProcessWire's limit selector field.
       * @var integer
       */
      'maxLimit' => 50,

      /**
       * Wheather the GraphiQL GUI should be stretched to full width or centered
       * like other parts of the ProcessWire's admin back-end.
       * @var boolean
       */
      'fullWidthGraphiQL' => true,

      /**
       * An array of template names that will be concidered for schema generation.
       * @var array
       */
      'legalTemplates' => [],

      /**
       * An array of field names that will be considered for schema generation.
       * @var array
       */
      'legalFields' => [],

      /**
       * An array of built-in Page field names that will be considered for schema
       * generation.
       * @var array
       */
      'legalPageFields' => [
        'created',
        'modified',
        'url',
        'id',
        'name',
        'httpUrl',
        ],

      /**
       * An array of built-in PageFile field names that will be considered for
       * schema createtion.
       * @var array
       */
      'legalPageFileFields' => [
        'url',
        'httpUrl',
        'description',
      ],

      /**
       * Grant access to everyone on a template level.
       * @var boolean
       */
      'grantTemplatesAccess' => false,

      /**
       * Grant access to everyone on a field level.
       * @var boolean
       */
      'grantFieldsAccess' => false,

      /**
       * The `pages` query field. Allows to perform $pages->find queries.
       * @var boolean
       */
      'pagesQuery' => false,

      /**
       * The `me` query field. Allows the user to query her credentials.
       * @var boolean
       */
      'meQuery' => true,

      /**
       * The `login` & `logout` fields. Provides authentication methods.
       * @var boolean
       */
      'authQuery' => true,
    );
  }

  /**
   * Checks if the given name is compatible with GraphQL.
   * @param  string  $name The name against the check will be performed.
   * @return boolean True if the name is compatible, false otherwise.
   */
	public static function isLegalTemplateName($name)
	{
		if (!$name) return false;
		if (preg_match('/^[_A-Za-z][-_0-9A-Za-z]*$/', $name) !== 1) return false; // the GraphQL naming requirement
		if (strpos($name, '__') === 0) return false; // the names with `__` prefix are reserved by GraphQL
    if (strpos($name, '--') === 0) return false; // as we change the `-` symbols to `_` for field names the `--` becomes `__` and it also reserved by GraphQL
		if (in_array($name, Utils::getReservedWords())) return false; // some words that used now and might be for future
    return true;
	}

  /**
   * Build module configuration.
   * @return InputFields ProcessWire Inputfields form.
   */
  public function getInputFields()
  {
    $inputfields = parent::getInputFields();

    // maxLimit
    $f = $this->modules->get('InputfieldInteger');
    $f->attr('name', 'maxLimit');
    $f->label = 'Max Limit';
    $f->description = 'Set the maximum value for `limit` selector field.';
    $f->required = true;
    $f->columnWidth = 50;
    $inputfields->add($f);

    // GraphiQL full width
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'fullWidthGraphiQL');
    $f->label = 'Full width GraphiQL';
    $f->description = 'Check this if you want GraphiQL on the backend to stretch to full width.';
    $f->columnWidth = 50;
    $inputfields->add($f);

    // legalTemplates
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalTemplates');
    $f->label = 'Legal Templates';
    $f->description = 'Only the templates that are selected here and have Access Control enabled will be handled by this module.';
    $gotDisabledFields = false;
    foreach (\ProcessWire\wire('templates') as $template) {
      $attributes = [];
      if (!self::isLegalTemplateName($template->name)) {
        $attributes['disabled'] = true;
        $gotDisabledFields = true;
      }
      $label = $template->flags & Template::flagSystem ? "{$template->name} `(system)`" : $template->name;
      $f->addOption($template->name, $label, $attributes);
    }
    $notes = "Please be careful with what you are exposing to the public. Choosing templates marked as `system` can lead to security vulnerabilities.";
    if ($gotDisabledFields) {
      $notes .= PHP_EOL;
      $notes .= "The template is disabled if it's name is incompatible or reserved for ProcessGraphQL module.";
    }
    $f->notes = $notes;
    $inputfields->add($f);

    // legalFields
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalFields');
    $f->label = 'Legal Fields';
    $f->description = 'The fields that you select below will be available via your GraphQL api.';
    $f->notes = 'Please be careful with what you are exposing to the public. Choosing fields marked as `system` can to lead security vulnerabilities.';
    foreach (\ProcessWire\wire('fields')->find("name!=pass") as $field) {
      if ($field->type instanceof FieldtypeFieldsetOpen) continue;
      if ($field->type instanceof FieldtypeFieldsetClose) continue;
      if ($field->type instanceof FieldtypeFieldsetTabOpen) continue;
      $f->addOption($field->name, $field->flags & Field::flagSystem ? "{$field->name} `(system)`" : $field->name);
    }
    $inputfields->add($f);

    // legalPageFields
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalPageFields');
    $f->label = 'Legal Page Fields';
    $f->description = 'Choose which built in `Page` fields you wish to be available via GraphQL api.';
    $f->notes = 'Be careful with fields like `parents` & `children` that will allow user to construct deeply nested queries that might be very expensive for your server to fulfill.';
    foreach (PageInterfaceType::getPageFields() as $fieldName => $fieldClassName) {
      $f->addOption($fieldName);
    }
    $inputfields->add($f);

    // legalPageFileFields
    $f = $this->modules->get('InputfieldCheckboxes');
    $f->optionColumns = 4;
    $f->attr('name', 'legalPageFileFields');
    $f->label = 'Legal PageFile Fields';
    $f->description = 'Choose which built in `PageFile` fields you wish to be available via GraphQL api.';
    foreach (PageFileInterfaceType::getPageFileFields() as $fieldName => $fieldClassName) {
      $f->addOption($fieldName);
    }
    $inputfields->add($f);

    // ADVANCED
    $fSet = $this->modules->get('InputfieldFieldset');
    $fSet->label = 'Advanced';
    $fSet->collapsed = Inputfield::collapsedYes;

    // meQuery
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'meQuery');
    $f->label = 'me Query';
    $f->columnWidth = 50;
    $desc = "Adds '`me`' query field. Allows user to query her credentials.";
    $f->description = $desc;
    $fSet->add($f);

    // authQuery
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'authQuery');
    $f->label = 'login/logout Query';
    $f->columnWidth = 50;
    $desc = "Adds '`login`' & '`logout`' fields. Allows users to authenticate via GraphQL API.";
    $f->description = $desc;
    $fSet->add($f);

    // grantTemplatesAccess
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'grantTemplatesAccess');
    $f->label = 'Grant Templates Access';
    $f->columnWidth = 50;
    $desc = "By default only `superuser` can access pages with template that ";
    $desc .= "does not have `Access` settings enabled. If you wish to grant ";
    $desc .= "access to pages without `Access` settings, check this field. ";
    $desc .= "(not recommended)";
    $f->description = $desc;
    $fSet->add($f);

    // grantFieldsAccess
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'grantFieldsAccess');
    $f->label = 'Grant Fields Access';
    $f->columnWidth = 50;
    $desc = "By default only `superuser` can access fields that does not have `Access` ";
    $desc .= "settings enabled. If you wish to grant access to fields without `Access` ";
    $desc .= "settings, check this field. (not recommended)";
    $f->description = $desc;
    $fSet->add($f);

    // pagesQuery
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'pagesQuery');
    $f->label = 'pages Query';
    $desc = "Experimental!" . PHP_EOL;
    $desc .= " Adds '`pages`' query field. ";
    $desc .= 'Allows you to fetch pages in ProcessWire\'s `$pages` style. ';
    $desc .= 'Like `$pages->find(...)`.';
    $f->description = $desc;
    $fSet->add($f);

    $inputfields->add($fSet);

    return $inputfields;
  }

}
