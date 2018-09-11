<?php
namespace Redaxscript\Modules\Demo;

use Redaxscript\Auth;
use Redaxscript\Db;
use Redaxscript\Installer;
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

	protected static $_moduleArray =
	[
		'name' => 'Demo',
		'alias' => 'Demo',
		'author' => 'Redaxmedia',
		'description' => 'Enable demo login',
		'version' => '4.0.0'
	];

	/**
	 * routeHeader
	 *
	 * @since 3.3.0
	 */

	public function routeHeader()
	{
		if ($this->_registry->get('firstParameter') === 'demo')
		{
			/* handle login */

			if ($this->_registry->get('secondParameter') === 'login')
			{
				$this->_registry->set('useTitle', $this->_language->get('login'));
				$this->_registry->set('routerBreak', true);
			}

			/* handle reinstall */

			if ($this->_registry->get('secondParameter') === 'reinstall')
			{
				$this->_registry->set('renderBreak', true);
				$this->_reinstall();
			}
		}
	}

	/**
	 * routeContent
	 *
	 * @since 3.3.0
	 */

	public function routeContent()
	{
		if ($this->_registry->get('firstParameter') === 'demo' && $this->_registry->get('secondParameter') === 'login')
		{
			echo $this->process();
		}
	}

	/**
	 * adminNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array|null
	 */

	public function adminNotification() : ?array
	{
		$auth = new Auth($this->_request);
		$auth->init();

		/* demo user */

		if ($auth->getUser('user') === 'demo')
		{
			$this->setNotification('success', $this->_language->get('logged_in') . $this->_language->get('point'));
		}
		return $this->getNotification();
	}

	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process() : string
	{
		$auth = new Auth($this->_request);
		$tableArray =
		[
			'categories',
			'articles',
			'extras',
			'comments',
			'groups',
			'users'
		];

		/* set the user */

		$auth->setUser('name', 'Demo');
		$auth->setUser('user', 'demo');
		$auth->setUser('email', 'demo@localhost');

		/* set the permission */

		foreach ($tableArray as $value)
		{
			$auth->setPermission($value,
			[
				1,
				2,
				3
			]);
		}
		$auth->setPermission('settings',
		[
			1
		]);

		/* save user and permission */

		$auth->save();

		/* handle success */

		if ($auth->getStatus())
		{
			return $this->_success();
		}
		return $this->_error();
	}

	/**
	 * success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _success() : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), 'admin')
			->doRedirect(0)
			->success($this->_language->get('logged_in'), $this->_language->get('welcome'));
	}

	/**
	 * error
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _error() : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'login')
			->doRedirect()
			->error($this->_language->get('something_wrong'), $this->_language->get('error_occurred'));
	}

	/**
	 * reinstall
	 *
	 * @since 2.4.0
	 */

	protected function _reinstall()
	{
		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$installer->rawDrop();
		$installer->rawCreate();
		$installer->insertData(
		[
			'adminName' => 'Admin',
			'adminUser' => 'admin',
			'adminPassword' => 'admin',
			'adminEmail' => 'admin@localhost'
		]);

		/* process modules */

		foreach ($this->_configArray['modules'] as $key => $moduleClass)
		{
			if (is_dir('modules/' . $key))
			{
				$module = new $moduleClass($this->_registry, $this->_request, $this->_language, $this->_config);
				$module->install();
			}
		}

		/* access and filter */

		Db::forTablePrefix('groups')
			->findMany()
			->set(
			[
				'modules' => null,
				'filter' => 1
			])
			->save();
	}
}
