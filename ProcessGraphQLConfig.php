<?php namespace ProcessWire;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\PageType;
use ProcessWire\GraphQL\Type\FileType;
use ProcessWire\GraphQL\Type\ImageType;

require_once $this->config->paths->site . 'modules/ProcessGraphQL/vendor/autoload.php';

class ProcessGraphQLConfig extends Moduleconfig {

  const generatorName = 'graphql_generate_pages';

  public function getDefaults()
  {
    return array(
      /**
       * Sets the max for ProcessWire's limit selector field.
       * @var integer
       */
      'maxLimit' => 50,

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
        'template',
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
       * An array of built-in PageImage field names that will be considered for
       * schema createtion.
       * @var array
       */
      'legalPageImageFields' => [
        'width',
        'height',
        'variations',
      ],

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

  public function generatePages()
  {

    $pageNames = [
      'graphql' => 'GraphQL',
      'graphiql' => 'GraphiQL',
    ];

    // copy the template files to site/templates folder
    try {
      $from = $this->config->paths->ProcessGraphQL . 'templates/';
      $to = $this->config->paths->templates;
      $this->files->copy($from, $to);
      $this->message(sprintf($this->_('Created template files: %s'), 'graphql.php & graphiql.php'));
    } catch(\Exception $e) {
      $this->error($e->getMessage()); 
    }
    
    foreach($pageNames as $name => $title) {

      // create the templates  
      try {
        $template = $this->wire(new Template());
        $template->name = $name;
        $fieldgroup = $this->wire(new Fieldgroup());
        $fieldgroup->name = $template->name;
        if ($this->fields->get('title') instanceof Field) $fieldgroup->add('title');
        $fieldgroup->save();
        $template->fieldgroup = $fieldgroup;
        $template->roles = array($this->roles->getGuestRole()->id);
        $template->noAppendTemplateFile = true;
        $template->noPrependTemplateFile = true;
        $template->save();
        $this->message(sprintf($this->_('Added template and fieldgroup: %s'), $name)); 
      } catch(\Exception $e) {
        $this->error($e->getMessage()); 
        continue; 
      }

      // create the pages
      try {
        $p = $this->wire(new Page());
        $p->template = $this->templates->get($name);
        $p->name = $name;
        $p->parent = $this->pages->get(1); // root page
        $p->title = $title;
        $p->save();
        $this->message(sprintf($this->_('Created page: %s'), $name)); 
      } catch(\Exception $e) {
        $this->error($e->getMessage()); 
        continue; 
      }
    }
  }

  public function readyToGeneratePages()
  {
    $generatorName = self::generatorName;
    if ($this->input->post->$generatorName) $this->generatePages();
    if (is_file($this->config->paths->templates . 'graphql.php')) return false;
    if (is_file($this->config->paths->templates . 'graphiql.php')) return false;
    if ($this->templates->get('graphql') instanceof Template) return false;
    if ($this->templates->get('graphiql') instanceof  Template)  return false;
    if (!$this->pages->get('/graphql/') instanceof NullPage) return false;
    if (!$this->pages->get('/graphiql/') instanceof NullPage) return false;
    return true;
  }

  /**
   * Build module configuration.
   * @return InputFields ProcessWire Inputfields form.
   */
  public function getInputFields()
  {
    $inputfields = parent::getInputFields();

    // legalTemplates
    $f = $this->modules->get('InputfieldAsmSelect');
    $f->optionColumns = 4;
    $f->attr('name', 'legalTemplates');
    $f->label = 'Templates';
    $f->description = 'Choose which page templates you want to be served via GraphQL api.';
    $gotDisabledFields = false;
    foreach (\ProcessWire\wire('templates') as $template) {
      // skip repeater templates
      if (Utils::isRepeaterTemplate($template)) {
        continue;
      }
      $attributes = [];
      if (!self::isLegalTemplateName($template->name)) {
        $attributes['disabled'] = true;
        $gotDisabledFields = true;
      }
      $label = $template->flags & Template::flagSystem ? "{$template->name} (system)" : $template->name;
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
    $f = $this->modules->get('InputfieldAsmSelect');
    $f->optionColumns = 4;
    $f->attr('name', 'legalFields');
    $f->label = 'Fields';
    $f->description = 'Choose which fields you want to be served via GraphQL api.';
    $f->notes = 'Please be careful with what you are exposing to the public. Choosing fields marked as `system` can to lead security vulnerabilities.';
    foreach (\ProcessWire\wire('fields')->find("name!=pass") as $field) {
      if ($field->type instanceof FieldtypeFieldsetOpen) continue;
      if ($field->type instanceof FieldtypeFieldsetClose) continue;
      if ($field->type instanceof FieldtypeFieldsetTabOpen) continue;
      $f->addOption($field->name, $field->flags & Field::flagSystem ? "{$field->name} (system)" : $field->name);
    }
    $inputfields->add($f);

    // legalPageFields
    $f = $this->modules->get('InputfieldAsmSelect');
    $f->optionColumns = 4;
    $f->attr('name', 'legalPageFields');
    $f->label = 'Page Fields';
    $f->description = 'Choose which built in `Page` fields you want to be served via GraphQL api.';
    $f->notes = 'Be careful with fields like `parents` & `children` that will allow user to construct deeply nested queries that might be very expensive for your server to fulfill.';
    foreach (PageType::getBuiltInFields() as $field) {
      $f->addOption($field['name']);
    }
    $inputfields->add($f);

    // legalPageFileFields
    $f = $this->modules->get('InputfieldAsmSelect');
    $f->optionColumns = 4;
    $f->attr('name', 'legalPageFileFields');
    $f->label = 'PageFile Fields';
    $f->description = 'Choose which built in `PageFile` fields you want to be served via GraphQL api.';
    $f->notes = 'These fields are also inherited by `PageImage`.';
    foreach (FileType::getBuiltInFields() as $field) {
      $f->addOption($field['name']);
    }
    $inputfields->add($f);

    // legalPageImageFields
    $f = $this->modules->get('InputfieldAsmSelect');
    $f->optionColumns = 4;
    $f->attr('name', 'legalPageImageFields');
    $f->label = 'PageImage Fields';
    $f->description = 'Choose which built in `PageImage` fields you want to be served via GraphQL api.';
    foreach (ImageType::getBuiltInFields() as $field) {
      $f->addOption($field['name']);
    }
    $inputfields->add($f);

    // GRAPHQL PAGES GENERATOR
    if ($this->readyToGeneratePages()) {
      $fSet = $this->modules->get('InputfieldFieldset');
      $fSet->label = 'GraphQL Pages Generator';
      $fSet->collapsed = Inputfield::collapsedYes;

      // graphql template name
      $f = $this->modules->get('InputfieldSubmit');
      $f->label = 'Generate';
      $f->description = 'Generates the graphql and graphiql templates, template files and pages.';
      $f->attr('name', self::generatorName);
      $f->attr('value', 'Generate');
      $fSet->add($f);

      $inputfields->add($fSet);
    }

    // maxLimit
    $f = $this->modules->get('InputfieldInteger');
    $f->attr('name', 'maxLimit');
    $f->label = 'Max Limit';
    $f->description = 'Set the maximum value for `limit` selector field for GraphQL api.';
    $f->required = true;
    $inputfields->add($f);

    // meQuery
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'meQuery');
    $f->label = 'me Query';
    $f->columnWidth = 50;
    $desc = "Adds `me` query field. Allows user to query her credentials.";
    $f->description = $desc;
    $inputfields->add($f);

    // authQuery
    $f = $this->modules->get('InputfieldCheckbox');
    $f->attr('name', 'authQuery');
    $f->label = 'login/logout Query';
    $f->columnWidth = 50;
    $desc = "Adds `login` & `logout` fields. Allows users to authenticate via GraphQL API.";
    $f->description = $desc;
    $inputfields->add($f);

    $inputfields->add($f);

    return $inputfields;
  }

}
