<?php
namespace Redaxscript\Modules\Demo;

use Redaxscript\Config as GlobalConfig;
use Redaxscript\Db;
use Redaxscript\Installer;
use Redaxscript\Language;
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

class Demo extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Demo',
		'alias' => 'Demo',
		'author' => 'Redaxmedia',
		'description' => 'Enable demo login',
		'version' => '2.6.0'
	);

	/**
	 * renderStart
	 *
	 * @since 2.4.0
	 */

	public static function renderStart()
	{
		if (Registry::get('firstParameter') === 'demo')
		{
			/* handle login */

			if (Registry::get('secondParameter') === 'login')
			{
				Registry::set('title', Language::get('login'));
				Registry::set('centerBreak', true);
			}

			/* handle reinstall */

			if (Registry::get('secondParameter') === 'reinstall')
			{
				self::_reinstall();
				Registry::set('renderBreak', true);
			}
		}
	}

	/**
	 * centerStart
	 *
	 * @since 2.4.0
	 */

	public static function centerStart()
	{
		if (Registry::get('firstParameter') === 'demo' && Registry::get('secondParameter') === 'login')
		{
			self::_login();
		}
	}

	/**
	 * login
	 *
	 * @since 2.4.0
	 */

	protected static function _login()
	{
		$root = Registry::get('root');
		$token = Registry::get('token');
		$tableArray = array(
			'categories',
			'articles',
			'extras',
			'comments',
			'groups',
			'users'
		);

		/* session values */

		Request::setSession($root . '/logged_in', $token);
		Request::setSession($root . '/my_name', 'Demo');
		Request::setSession($root . '/my_user', 'demo');
		Request::setSession($root . '/my_email', 'demo@localhost');
		foreach ($tableArray as $value)
		{
			Request::setSession($root . '/' . $value . '_new', 1);
			Request::setSession($root . '/' . $value . '_edit', 1);
			Request::setSession($root . '/' . $value . '_delete', 1);
		}
		Request::setSession($root . '/modules_install', 0);
		Request::setSession($root . '/modules_edit', 0);
		Request::setSession($root . '/modules_uninstall', 0);
		Request::setSession($root . '/settings_edit', 1);
		Request::setSession($root . '/filter', 1);

		/* notification */

		notification(Language::get('welcome'), Language::get('logged_in'), Language::get('continue'), 'admin');
	}

	/**
	 * reinstall
	 *
	 * @since 2.4.0
	 */

	protected static function _reinstall()
	{
		$installer = new Installer(GlobalConfig::getInstance());
		$installer->init();
		$installer->rawDrop();
		$installer->rawCreate();
		$installer->insertData(array(
			'adminName' => 'Admin',
			'adminUser' => 'admin',
			'adminPassword' => 'admin',
			'adminEmail' => 'admin@localhost'
		));

		/* process modules */

		foreach (self::$_config['modules'] as $key => $value)
		{
			if (is_dir('modules/' . $key))
			{
				$module = new $value;
				$module->install();
			}
		}

		/* access and filter */

		Db::forTablePrefix('groups')
			->findMany()
			->set(array(
				'modules' => null,
				'filter' => 1
			))
			->save();
	}
}
