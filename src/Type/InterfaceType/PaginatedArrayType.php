<?php

namespace ProcessWire\GraphQL\Type\InterfaceType;

use Youshido\GraphQL\Type\InterfaceType\AbstractInterfaceType;
use Youshido\GraphQL\Type\Scalar\IntType;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\GraphQL\Type\Object\TemplatedPageArrayType;

class PaginatedArrayType extends AbstractInterfaceType {

  public function getName()
  {
    return 'PaginatedArrayInterface';
  }

  public function getDescription()
  {
    return 'ProcessWire PaginatedArray interface. Provide data for pagination.';
  }

  public function build($config)
  {
    $maxLimit = Utils::moduleConfig()->maxLimit;
    $config->addFields([
      
      // \ProcessWire\PaginatedArray::getTotal()
      'getTotal' => [
        'type' => new IntType(),
        'description' => 'Get the total number of pages that were found from a $pages->find("selectors, limit=n") 
                          operation that led to this PageArray. The number returned may be greater than the number 
                          of pages actually in PageArray, and is used for calculating pagination. 
                          Whereas `count` will always return the number of pages actually in PageArray.',
        'resolve' => function ($value) {
          return (integer) $value->getTotal();
        }
      ],

      // \ProcessWire\PaginatedArray::getLimit()
      'getLimit' => [
        'type' => new IntType(),
        'description' => "Get the number (n) from a 'limit=n' portion of a selector that resulted in the PageArray.
                          In pagination, this value represents the max items to display per page. The default limit
                          is set to $maxLimit.",
        'resolve' => function ($value) {
          return (integer) $value->getLimit();
        }
      ],

      // \ProcessWire\PaginatedArray::getStart()
      'getStart' => [
        'type' => new IntType(),
        'description' => "Get the number of the starting result that led to the PageArray in pagination. 
                          Returns 0 if in the first page of results.",
        'resolve' => function ($value) {
          return (integer) $value->getStart();
        }
      ]
    ]);
  }

  public function resolveType($pageArray)
  {
    // get the template selector field
    $templateSelector = SelectorType::findSelectorByField($pageArray->getSelectors(), 'template');

    // if there is only one template selected then we can assume it is a TemplatedPageArray
    if (count($templateSelector->values) === 1) {
      $template = Utils::moduleConfig()->legalViewTemplates->get($templateSelector->values[0]);
      return new TemplatedPageArrayType($template);
    }
    return new PageArrayType();
  }

}