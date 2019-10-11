<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Traits;

use \ProcessWire\GraphQL\Utils;

trait AccessTrait {

  public static function setUpAccess()
  {    
    if (isset(self::accessRules['legalTemplates'])) {
      Utils::module()->legalTemplates = array_merge(Utils::module()->legalTemplates, self::accessRules['legalTemplates']);
    }
    
    if (isset(self::accessRules['legalFields'])) {
      Utils::module()->legalFields = array_merge(Utils::module()->legalFields, self::accessRules['legalFields']);
    }
    
    if (isset(self::accessRules['legalPageFields'])) {
      Utils::module()->legalPageFields = array_merge(Utils::module()->legalPageFields, self::accessRules['legalPageFields']);
    }
    
    if (isset(self::accessRules['legalPageFileFields'])) {
      Utils::module()->legalPageFileFields = array_merge(Utils::module()->legalPageFileFields, self::accessRules['legalPageFileFields']);
    }
    
    if (isset(self::accessRules['legalPageImageFields'])) {
      Utils::module()->legalPageImageFields = array_merge(Utils::module()->legalPageImageFields, self::accessRules['legalPageImageFields']);
    }

    Utils::session()->login('admin', Utils::config()->testUsers['admin']);
  }

  public static function tearDownAccess()
  {
    Utils::session()->logout();
  }

}