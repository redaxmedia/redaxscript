<?php
namespace Redaxscript;

use Redaxscript\Modules\FormValidator;
use function set_include_path;

/* bootstrap */

include_once('includes' . DIRECTORY_SEPARATOR . 'bootstrap.php');

/* header */

Header::init();

/* get instance */

$config = Config::getInstance();

/* load module */

$formValidator = new FormValidator\FormValidator(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
$formValidator->renderStart();

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
