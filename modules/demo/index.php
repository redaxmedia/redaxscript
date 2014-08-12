<?php
namespace Redaxscript\Modules;
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
		if (Registry::get('firstParameter') === 'login' && Registry::get('secondParameter') === 'demo' || Registry::get('adminParameter') === 'unpublish' && Registry::get('myUser') === 'demo')
		{
			Registry::set('centerBreak', 1);
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
			self::demoLogin();
		}

		/* disable unpublish */

		if (Registry::get('adminParameter') === 'unpublish' && Registry::get('myUser') === 'demo')
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
		$root = Registry::get('root');
		$token = Registry::get('token');

		/* session values */

		Request::setSession($root . '/logged_in', $token);
		Request::setSession($root . '/my_name', 'Anonymous');
		Request::setSession($root . '/my_user', 'demo');
		Request::setSession($root . '/my_email', 'anonymous@demo.com');
		Request::setSession($root . '/categories_new', 1);
		Request::setSession($root . '/categories_edit', 1);
		Request::setSession($root . '/articles_new', 1);
		Request::setSession($root . '/articles_edit', 1);
		Request::setSession($root . '/comments_new', 1);
		Request::setSession($root . '/comments_edit', 1);
		Request::setSession($root . '/settings_edit', 1);
		Request::setSession($root . '/filter', 1);

		/* notification */

		notification(l('welcome'), l('logged_in'), l('continue'), 'admin');
	}
}
