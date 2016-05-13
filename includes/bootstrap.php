<?php
namespace Redaxscript;

/* autoload */

include_once('includes/Autoloader.php');

/* deprecated */

include_once('includes/admin_admin.php');
include_once('includes/admin_list.php');
include_once('includes/admin_query.php');
include_once('includes/admin_router.php');
include_once('includes/contents.php');
include_once('includes/loader.php');
include_once('includes/navigation.php');
include_once('includes/query.php');
include_once('includes/startup.php');
include_once('includes/comments.php');

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

/* startup and registry */

startup();
$registry->init();

/* deprecated */

if ($registry->get('file') === 'index.php')
{
	include_once('includes/head.php');
	include_once('includes/router.php');
}

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
