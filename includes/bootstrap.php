<?php
namespace Redaxscript;

/* include as needed */

include_once('includes/Autoloader.php');

/* init */

Autoloader::init();
Request::init();

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$config = Config::getInstance();
$config::init();

/* database */

Db::init($config);

/* set database status */

$registry->set('dbStatus', Db::getStatus());

/* database has tables */

if ($registry->get('dbStatus') === 2)
{
	/* startup and migrate */

	startup();
	$registry->init(migrate_constants());

	/* hook */

	Hook::init($registry);
}

/* detector */

$detectorLanguage = new Detector\Language($registry, $request);
$detectorTemplate = new Detector\Template($registry, $request);

/* set language and template */

$registry->set('language', $detectorLanguage->getOutput());
$registry->set('template', $detectorTemplate->getOutput());

/* language */

$language = Language::getInstance();
$language->init($registry->get('language'));