<?php

/**
 * demo center start
 */

function demo_center_start()
{
	if (FIRST_PARAMETER == 'login' && SECOND_PARAMETER == 'demo')
	{
		define('CENTER_BREAK', 1);
		demo_login();
	}
}

/**
 * demo login
 */

function demo_login()
{
	$_SESSION[ROOT . '/logged_in'] = TOKEN;
	$_SESSION[ROOT . '/my_id'] = 0;
	$_SESSION[ROOT . '/my_name'] = 'Anonymous';
	$_SESSION[ROOT . '/my_user'] = 'anonymous';
	$_SESSION[ROOT . '/my_email'] = 'anonymous@anonymous.com';
	$_SESSION[ROOT . '/categories_new'] = 1;
	$_SESSION[ROOT . '/categories_edit'] = 1;
	$_SESSION[ROOT . '/categories_delete'] = 0;
	$_SESSION[ROOT . '/articles_new'] = 1;
	$_SESSION[ROOT . '/articles_edit'] = 1;
	$_SESSION[ROOT . '/articles_delete'] = 0;
	$_SESSION[ROOT . '/comments_new'] = 1;
	$_SESSION[ROOT . '/comments_edit'] = 1;
	$_SESSION[ROOT . '/comments_delete'] = 0;
	$_SESSION[ROOT . '/settings_edit'] = 1;
	$_SESSION[ROOT . '/filter'] = 1;
	notification(l('welcome'), l('logged_in'), l('continue'), 'admin');
}
?>