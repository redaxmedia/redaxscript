<?php
namespace Redaxscript;

use PDO;
use PDOException;

/* include as needed */

include_once('includes/Autoloader.php');

/* init */

Autoloader::init();
Request::init();

/* registry and config */

$registry = Registry::getInstance();
$config = Config::getInstance();

/* database */

Db::init($config);

/* database status */

try
{
	/* has connection */

	if ($config->get('type') === Db::getDb()->getAttribute(PDO::ATTR_DRIVER_NAME))
	{
		$registry->set('dbStatus', 1);

		/* has tables */

		if (Db::forTablePrefix()->rawQuery('SHOW TABLES LIKE ' . $config->get('prefix') . '%')->findMany()->count())
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

$detectorLanguage = new Detector\Language($registry);
$detectorTemplate = new Detector\Template($registry);

/* set language and template */

$registry->set('language', $detectorLanguage->getOutput());
$registry->set('template', $detectorTemplate->getOutput());

/* language */

$language = Language::getInstance();
$language->init($registry->get('language'));

/* define deprecated constants */

define('LANGUAGE', $detectorLanguage->getOutput());
define('TEMPLATE', $detectorTemplate->getOutput());