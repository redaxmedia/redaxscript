<?php
error_reporting(E_ERROR || E_PARSE);

include_once('includes/Singleton.php');
include_once('includes/Config.php');

/* bootstrap */

include_once('includes/bootstrap.php');

/* module init */

Redaxscript\Hook::trigger('init');

/* call loader else render template */

if (Redaxscript\Registry::get('firstParameter') == 'loader' && (Redaxscript\Registry::get('secondParameter') == 'styles' || Redaxscript\Registry::get('secondParameter') == 'scripts'))
{
	echo loader(Redaxscript\Registry::get('secondParameter'), 'outline');
}
else
{
	include_once('templates/install/install.phtml');
}

/**
 * router
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function router()
{
	/* check token */

	if ($_POST && $_POST['token'] != Redaxscript\Registry::get('token'))
	{
		$output = '<div class="rs-box-note rs-note-error">' . Redaxscript\Language::get('token_incorrect') . Redaxscript\Language::get('point') . '</div>';
		echo $output;
		return;
	}

	$installController = new Redaxscript\Controller\Install(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance(), Redaxscript\Request::getInstance());
	$installController->_config = \Redaxscript\Config::getInstance();
	echo $installController->process();
}
