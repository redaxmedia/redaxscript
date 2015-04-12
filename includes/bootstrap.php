<?php
namespace Redaxscript;

use PDO;
use PDOException;

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

/* database status */

try
{
	/* has connection */

	if ($config->get('type') === Db::getDb()->getAttribute(PDO::ATTR_DRIVER_NAME))
	{
		$registry->set('dbStatus', 1);

		/* has table */

		if (Db::countTablePrefix())
		{
			$registry->set('dbStatus', 2);
		}
	}
}

/* catch pdo exception */

catch (PDOException $exception)
{
	$registry->set('dbException', $exception->getMessage());
}

/* startup and migrate constants */

startup();
$registry->init(migrate_constants());

/* hook */

if ($registry->get('dbStatus') === 2)
{
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