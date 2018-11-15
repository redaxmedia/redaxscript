<?php
namespace Redaxscript;

use function error_reporting;
use function getenv;

error_reporting(getenv('DEBUG') ? E_DEPRECATED | E_WARNING | E_ERROR | E_PARSE : 0);

/* include */

include_once('includes' . DIRECTORY_SEPARATOR . 'Autoloader.php');

/* autoload */

$autoloader = new Autoloader();
$autoloader->init();

/* get the instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$config = Config::getInstance();

/* registry and request and config */

$registry->init();
$request->init();
$config->init();

/* database */

Db::construct($config);
Db::configure('logging', getenv('DEBUG'));
Db::init();

/* bootstrap */

new Bootstrap\Config($registry, $request);
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

if ($registry->get('dbStatus') > 1)
{
	Module\Hook::construct($registry, $request, $language, $config);
	Module\Hook::init();
	Module\Hook::trigger('init');
}

/* router */

if ($registry->get('token') === $registry->get('loggedIn'))
{
	$adminRouter = new Admin\Router\Router($registry, $request, $language, $config);
	$adminRouter->init();
	$adminRouter->routeHeader();
}
$router = new Router\Router($registry, $request, $language, $config);
$router->init();
$router->routeHeader();
