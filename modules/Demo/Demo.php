<?php
namespace Redaxscript\Modules\Demo;

use Redaxscript\Auth;
use Redaxscript\Config as BaseConfig;
use Redaxscript\Db;
use Redaxscript\Installer;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Messenger as Messenger;

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
		'version' => '3.0.0'
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
				Registry::set('metaTitle', Language::get('login'));
				Registry::set('routerBreak', true);
			}

			/* handle reinstall */

			if (Registry::get('secondParameter') === 'reinstall')
			{
				Registry::set('renderBreak', true);
				self::_reinstall();
			}
		}
	}

	/**
	 * routerStart
	 *
	 * @since 3.0.0
	 */

	public static function routerStart()
	{
		if (Registry::get('firstParameter') === 'demo' && Registry::get('secondParameter') === 'login')
		{
			echo self::process();
		}
	}

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 *
	 * return array
	 */

	public static function adminPanelNotification()
	{
		$auth = new Auth(Request::getInstance());
		$auth->init();

		/* demo user */

		if ($auth->getUser('user') === 'demo')
		{
			self::setNotification('success', Language::get('logged_in') . Language::get('point'));
		}
		return self::getNotification();
	}

	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function process()
	{
		$auth = new Auth(Request::getInstance());
		$tableArray = array(
			'categories',
			'articles',
			'extras',
			'comments',
			'groups',
			'users'
		);

		/* set user */

		$auth->setUser('name', 'Demo');
		$auth->setUser('user', 'demo');
		$auth->setUser('email', 'demo@localhost');

		/* set permission */

		foreach ($tableArray as $value)
		{
			$auth->setPermission($value, array(
				1,
				2,
				3
			));
		}
		$auth->setPermission('settings', array(
			1
		));

		/* save user and permission */

		$auth->save();

		/* handle success */

		if ($auth->getStatus())
		{
			return self::_success();
		}
		return self::_error();
	}

	/**
	 * success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected static function _success()
	{
		$messenger = new Messenger(Registry::getInstance());
		return $messenger
			->setRoute(Language::get('continue'), 'admin')
			->doRedirect(0)
			->success(Language::get('logged_in'), Language::get('welcome'));
	}

	/**
	 * error
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected static function _error()
	{
		$messenger = new Messenger(Registry::getInstance());
		return $messenger
			->setRoute(Language::get('back'), 'login')
			->doRedirect()
			->error(Language::get('something_wrong'), Language::get('error_occurred'));
	}

	/**
	 * reinstall
	 *
	 * @since 2.4.0
	 */

	protected static function _reinstall()
	{
		$installer = new Installer(BaseConfig::getInstance());
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

		foreach (self::$_configArray['modules'] as $key => $value)
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
