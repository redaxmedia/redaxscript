<?php
namespace Redaxscript;

/* include files */

include_once('includes/Autoloader.php');

/* init */

Autoloader::init();
Request::init();

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$config = Config::getInstance();

/* config and database */

$config->init();
Db::construct($config);
Db::init();

/* startup and migrate */

startup();
$registry->init(migrate_constants());

/* hook */

if ($registry->get('dbStatus') === 2)
{
	Hook::construct($registry);
	Hook::init();
}

/* refresh */

Request::refreshSession();

/* detector */

$detectorLanguage = new Detector\Language($registry, $request);
$detectorTemplate = new Detector\Template($registry, $request);

/* set language and template */

$registry->set('language', $detectorLanguage->getOutput());
$registry->set('template', $detectorTemplate->getOutput());

/* language */

$language = Language::getInstance();
$language->init($registry->get('language'));
