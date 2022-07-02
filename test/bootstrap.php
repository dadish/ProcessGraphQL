<?php namespace ProcessWire\GraphQL\Test;

// start the PHP session
ob_start();

// increase the php memory limit for testing
ini_set('memory_limit', '512M');

// paths
$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");
$siteDir = realpath($pwDir . "/site-blank/");
$moduleDir = $siteDir . "/modules/ProcessGraphQL";
$testFilesDir = realpath($baseDir . "/test/files");
$siteFilesDir = $siteDir . "/assets/files";

// load dependencies
require_once $baseDir . "/vendor/autoload.php";

// remove install.php otherwise PW will think the site needs to be installed
$installFile = $pwDir . "/install.php";
if (file_exists($installFile)) {
	unlink($installFile);
}

// overwrite site-blank's config.php with our own custom one
copy(__DIR__ . "/site/config.php", $siteDir . "/config.php");

// symlink site/classes directory
$classesDir = __DIR__ . "/site/classes";
$classesDestDir = $siteDir . "/classes";
if (!file_exists($classesDestDir)) {
	\symlink($classesDir, $classesDestDir);
}

// create necessary asset dirs
$sessionsDir = $siteDir . "/assets/sessions";
if (!file_exists($sessionsDir)) {
	mkdir($sessionsDir);
}

// symlink our module inside the site's modules
// directory, so we can install it as a module to
// our processwire instance
if (!file_exists($moduleDir)) {
  \symlink($baseDir, $moduleDir);
}

// symlink FieldtypeMapMarker inside the site's module
// directory, so we can test FieldtypeMapMarker field.
$mapMarkerDir = $baseDir . "/vendor/ryancramerdesign/fieldtypemapmarker";
$mapMarkerDestDir = $siteDir . "/modules/FieldtypeMapMarker";
if (!file_exists($mapMarkerDestDir)) {
	\symlink($mapMarkerDir, $mapMarkerDestDir);
}

// symlink GraphQLFieldtypeMapMarker inside the site's module
// directory, so we can test FieldtypeMapMarker field.
$mapMarkerGraphQLDir = $baseDir . "/vendor/dadish/graphqlfieldtypemapmarker";
$mapMarkerGraphQLDestDir = $siteDir . "/modules/GraphQLFieldtypeMapMarker";
if (!file_exists($mapMarkerGraphQLDestDir)) {
	\symlink($mapMarkerGraphQLDir, $mapMarkerGraphQLDestDir);
}

// symlink skyscrapers pages files to site's asset files
if (!file_exists($siteFilesDir)) {
	\symlink($testFilesDir, $siteFilesDir);
}

use ProcessWire\ProcessWire;

$config = ProcessWire::buildConfig($pwDir, null, [
  "siteDir" => "site-blank"
]);

require_once realpath(__DIR__ . "/databaseReset.php");

// Fire up ProcessWire!!!
$wire = new ProcessWire($config);
$modules = $wire->fuel('modules');
$modules->refresh();

$module = $modules->get('ProcessGraphQL');
$module->install();

// set output formatting
$pages = $wire->fuel('pages');
$pages->setOutputFormatting(true);

// set $page wire
$wire->wire('page', $pages->get('/'));

// disable cache for $pages->get() & $pages->find() if they have random sorting
$pages->addHookBefore('find', function ($event) {
	$selector = $event->arguments('selector');
	if (strpos($selector, 'sort=random')) {
		$hash = uniqid();
		$selector .= ", id!=$hash";
		$event->arguments('selector', $selector);
	}
});

// include phpunit assertion functions
require_once realpath("$baseDir/vendor/phpunit/phpunit/src/Framework/Assert/Functions.php");

// include custom assertion functions
require_once realpath("$baseDir/test/Assert/Functions.php");