<?php

namespace ProcessWire\GraphQL\Field\Pages;

use ProcessWire\GraphQL\Field\PageArray\PageArrayFindField;

class PagesFindField extends PageArrayFindField {

  public function getDescription()
  {
    return 'Allows to search for all pages in the ProcessWire app.';
  }

}