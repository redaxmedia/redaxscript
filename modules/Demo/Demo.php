<?php
namespace Redaxscript\Modules\Demo;

use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * enable anonymous login
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Demo extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Demo',
		'alias' => 'Demo',
		'author' => 'Redaxmedia',
		'description' => 'Enable anonymous login',
		'version' => '2.4.0',
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
		if (Registry::get('firstParameter') === 'login' && Registry::get('secondParameter') === 'demo' || Registry::get('adminParameter') === 'unpublish' && Registry::get('myUser') === 'demo')
		{
			Registry::set('centerBreak', true);
		}
	}

	/**
	 * centerStart
	 *
	 * @since 2.2.0
	 */

	public static function centerStart()
	{
		/* trigger login */

		if (Registry::get('firstParameter') === 'login' && Registry::get('secondParameter') === 'demo')
		{
			self::_login();
		}

		/* disable unpublish */

		if (Registry::get('adminParameter') === 'unpublish' && Registry::get('myUser') === 'demo')
		{
			notification(Language::get('error_occurred'), Language::get('access_no'), Language::get('back'), 'admin');
		}
	}

	/**
	 * login
	 *
	 * @since 2.2.0
	 */

	protected static function _login()
	{
		$root = Registry::get('root');
		$token = Registry::get('token');

		/* session values */

		Request::setSession($root . '/logged_in', $token);
		Request::setSession($root . '/my_name', 'Anonymous');
		Request::setSession($root . '/my_user', 'demo');
		Request::setSession($root . '/my_email', 'anonymous@localhost');
		Request::setSession($root . '/categories_new', 1);
		Request::setSession($root . '/categories_edit', 1);
		Request::setSession($root . '/articles_new', 1);
		Request::setSession($root . '/articles_edit', 1);
		Request::setSession($root . '/comments_new', 1);
		Request::setSession($root . '/comments_edit', 1);
		Request::setSession($root . '/settings_edit', 1);
		Request::setSession($root . '/filter', 1);

		/* notification */

		notification(Language::get('welcome'), Language::get('logged_in'), Language::get('continue'), 'admin');
	}
}
