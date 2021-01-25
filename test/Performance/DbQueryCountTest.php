<?php

namespace ProcessWire\GraphQL\Test\Performance;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

/**
 * @group performance
 */
class DbQueryCountTest extends GraphQLTestCase
{
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["architect", "skyscraper"],
      "legalFields" => [
        "title",
        "options",
        "images",
        "architects",
        "born",
        "resume",
        "height",
        "floors",
        "year",
      ],
      "maxLimit" => 1000,
    ];
  }

  /**
   * The lowest database query count so far. Taken from executing the $performanceQuery query.
   *
   * @var integer
   */
  public static $bestQueryCount = 29;

  /**
   * The query we use to test the performance of the module by counting
   * the queries to the database.
   *
   * @var string
   */
  public static $performanceQuery = [
    "query" => 'query getSkyscrapers($selector: Selector!) {
      skyscraper(s: $selector) {
        getTotal
        list{
          id
          title
          height
          floors
          year
          images {
            url
            width
            height
            description
          }
          architects{
            list{
              id
              title
              created
              born
              resume{
                url
                description
              }
            }
          }
        }
      }
    }',
    "variables" => [
      "selector" => "architects.count>1",
    ],
  ];

  public function testDbQueryCount()
  {
    $queryCountStart = count(Utils::database()->queryLog());
    $res = self::execute(
      self::$performanceQuery["query"],
      self::$performanceQuery["variables"]
    );
    $queryCountEnd = count(Utils::database()->queryLog());
    $queryCount = $queryCountEnd - $queryCountStart;

    // report
    $queryCountReport = "Query Count:\n$queryCount\n";
    $improvementPercent = (1 - $queryCount / self::$bestQueryCount) * 100;
    $performanceChangeReport = "\nPerformance Improvement\n$improvementPercent%";
    \ProcessWire\GraphQL\log("$queryCountReport$performanceChangeReport");

    // assert performance
    self::assertLessThanOrEqual(self::$bestQueryCount, $queryCount);
    self::assertGreaterThanOrEqual(
      self::$bestQueryCount / 5,
      $queryCount,
      "It can't be true! Performance increased five times!"
    );

    // assert result count
    $skyscrapers = Utils::pages()->find(
      self::$performanceQuery["variables"]["selector"]
    );
    self::assertEquals(
      $skyscrapers->count(),
      count($res->data->skyscraper->list),
      "Incorrect number of skyscrapers fetched."
    );

    // assert no errors
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");

    // assert valid skyscraper
    $expected = $skyscrapers->get("images.count>1, sort=random");
    self::assertNotNull($expected, "No expected skyscraper to check.");
    $actual = self::getListItemId($res->data->skyscraper->list, $expected->id);
    self::assertNotNull($actual, "No actual skyscraper to check.");
    self::assertEquals($actual->id, $expected->id, "Incorrect id.");
    self::assertEquals($actual->title, $expected->title, "Incorrect title.");
    self::assertEquals($actual->height, $expected->height, "Incorrect height.");
    self::assertEquals($actual->floors, $expected->floors, "Incorrect floors.");
    self::assertEquals($actual->year, $expected->year, "Incorrect year.");

    // assert valid skyscraper images data
    self::assertEquals(
      count($expected->images),
      count($actual->images),
      "Incorrect images amount."
    );
    self::assertEquals(
      $expected->images->first()->url,
      $actual->images[0]->url,
      "Incorrect image url."
    );
    self::assertEquals(
      $expected->images->first()->width,
      $actual->images[0]->width,
      "Incorrect image width."
    );
    self::assertEquals(
      $expected->images->first()->height,
      $actual->images[0]->height,
      "Incorrect image height."
    );
    self::assertEquals(
      $expected->images->first()->description,
      $actual->images[0]->description,
      "Incorrect image description."
    );

    // assert valid skyscraper architects data
    $expected = $skyscrapers->find("architects.count>0");
    $arhitectsIds = $expected->implode("|", "architects");
    $architectsWithResumes = Utils::pages()->find(
      "id=$arhitectsIds, resume.count>0"
    );
    $expected = $skyscrapers->get(
      "architects=$architectsWithResumes, sort=random"
    );
    self::assertNotNull($expected, "No expected skyscraper to check.");
    $actual = self::getListItemId($res->data->skyscraper->list, $expected->id);
    self::assertEquals(
      count($expected->architects),
      count($actual->architects->list),
      "Incorrect architects amount."
    );
    self::assertEquals(
      $expected->architects[0]->id,
      $actual->architects->list[0]->id,
      "Incorrect architect id."
    );
    self::assertEquals(
      $expected->architects[0]->title,
      $actual->architects->list[0]->title,
      "Incorrect architect title."
    );
    self::assertEquals(
      $expected->architects[0]->created,
      $actual->architects->list[0]->created,
      "Incorrect architect created."
    );
    self::assertEquals(
      $expected->architects[0]->born,
      $actual->architects->list[0]->born,
      "Incorrect architect born."
    );
    self::assertEquals(
      count($expected->architects[0]->resume),
      count($actual->architects->list[0]->resume),
      "Incorrect architects resume amount."
    );
    self::assertEquals(
      $expected->architects[0]->resume->first()->url,
      $actual->architects->list[0]->resume[0]->url,
      "Incorrect architect resume url."
    );
    self::assertEquals(
      $expected->architects[0]->resume->first()->description,
      $actual->architects->list[0]->resume[0]->description,
      "Incorrect architect resume description."
    );
  }

  public static function getListItemId($list, $id)
  {
    foreach ($list as $item) {
      if ((int) $item->id === (int) $id) {
        return $item;
      }
    }
    return null;
  }
}
