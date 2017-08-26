<?php
namespace Redaxscript;

error_reporting(E_ERROR | E_PARSE);

/* include */

include_once('includes' . DIRECTORY_SEPARATOR . 'Autoloader.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'admin_admin.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'admin_list.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'admin_query.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'admin_router.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'comments.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'contents.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'navigation.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'query.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'router.php');

/* autoload */

$autoloader = new Autoloader();
$autoloader->init();

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$config = Config::getInstance();

/* registry and request and config */

$registry->init();
$request->init();
$config->init();

/* database */

Db::construct($config);
Db::init();

/* bootstrap */

new Bootstrap\Config();
new Bootstrap\Session($registry, $request);
new Bootstrap\Common($registry, $request);
new Bootstrap\Status($registry, $request);
new Bootstrap\Router($registry, $request);
new Bootstrap\Auth($registry, $request);
new Bootstrap\Content($registry, $request);
new Bootstrap\Cronjob($registry, $request);
new Bootstrap\Cache($registry, $request);
new Bootstrap\Detector($registry, $request);

/* language */

$language = Language::getInstance();
$language->init($registry->get('language'));

/* module hook */

if ($registry->get('dbStatus') === 2)
{
	Module\Hook::construct($registry, $request, $language, $config);
	Module\Hook::init();
	Module\Hook::trigger('init');
}
