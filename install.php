<?php
namespace Redaxscript;

use function set_include_path;

/* bootstrap */

include_once('includes' . DIRECTORY_SEPARATOR . 'bootstrap.php');

/* header */

Header::init();

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$language = Language::getInstance();
$config = Config::getInstance();

/* load module */

$formValidator = new Modules\FormValidator\FormValidator($registry, $request, $language, $config);
$unmaskPassword = new Modules\UnmaskPassword\UnmaskPassword($registry, $request, $language, $config);
$formValidator->renderStart();
$unmaskPassword->renderStart();

/* restrict access */

if ($config->get('env') !== 'production')
{
	Header::responseCode(200);
	set_include_path('templates');
	include_once('install' . DIRECTORY_SEPARATOR . 'index.phtml');
}
else
{
	Header::responseCode(403);
	exit;
}
