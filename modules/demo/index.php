<?php

class Redaxscript_Module_Demo extends Redaxscript_Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Demo',
		'alias' => 'demo',
		'author' => 'Redaxmedia',
		'description' => 'Enable anonymous login',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (FIRST_PARAMETER == 'login' && SECOND_PARAMETER == 'demo' || ADMIN_PARAMETER == 'unpublish' && MY_ID == 0)
		{
			define('CENTER_BREAK', 1);
		}
	}

	/**
	 * centerStart
	 *
	 * @since 2.2.0
	 */

	public static function centerStart()
	{
		if (FIRST_PARAMETER == 'login' && SECOND_PARAMETER == 'demo')
		{
			self::demoLogin();
		}
		if (ADMIN_PARAMETER == 'unpublish' && MY_ID == 0)
		{
			notification(l('error_occurred'), l('access_no'), l('back'), 'admin');
		}
	}

	/**
	 * demoLogin
	 *
	 * @since 2.2.0
	 */

	public static function demoLogin()
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
		$_SESSION[ROOT . '/extras_new'] = 0;
		$_SESSION[ROOT . '/extras_edit'] = 0;
		$_SESSION[ROOT . '/extras_delete'] = 0;
		$_SESSION[ROOT . '/comments_new'] = 1;
		$_SESSION[ROOT . '/comments_edit'] = 1;
		$_SESSION[ROOT . '/comments_delete'] = 0;
		$_SESSION[ROOT . '/groups_new'] = 0;
		$_SESSION[ROOT . '/groups_edit'] = 0;
		$_SESSION[ROOT . '/groups_delete'] = 0;
		$_SESSION[ROOT . '/users_new'] = 0;
		$_SESSION[ROOT . '/users_edit'] = 0;
		$_SESSION[ROOT . '/users_delete'] = 0;
		$_SESSION[ROOT . '/modules_install'] = 0;
		$_SESSION[ROOT . '/modules_edit'] = 0;
		$_SESSION[ROOT . '/modules_uninstall'] = 0;
		$_SESSION[ROOT . '/settings_edit'] = 1;
		$_SESSION[ROOT . '/filter'] = 1;
		notification(l('welcome'), l('logged_in'), l('continue'), 'admin');
	}
}