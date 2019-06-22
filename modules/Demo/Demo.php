<?php
namespace Redaxscript\Modules\Demo;

use Redaxscript\Auth;
use Redaxscript\Db;
use Redaxscript\Installer;
use Redaxscript\Module;
use Redaxscript\View;
use function is_dir;

/**
 * enable anonymous login
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Demo extends Module\Notification
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
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'modules' =>
		[
			'Analytics' => 'Redaxscript\Modules\Analytics\Analytics',
			'Demo' => 'Redaxscript\Modules\Demo\Demo',
			'Editor' => 'Redaxscript\Modules\Editor\Editor'
		]
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'demo' && $this->_registry->get('thirdParameter') === 'reinstall')
		{
			$this->_registry->set('renderBreak', true);
			$this->_reinstall();
		}
	}

	/**
	 * routeHeader
	 *
	 * @since 3.3.0
	 */

	public function routeHeader() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'demo' && $this->_registry->get('thirdParameter') === 'login')
		{
			$this->_registry->set('useTitle', $this->_language->get('login'));
			$this->_registry->set('routerBreak', true);
		}
	}

	/**
	 * routeContent
	 *
	 * @since 3.3.0
	 */

	public function routeContent() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'demo' && $this->_registry->get('thirdParameter') === 'login')
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
	 * messenger factory
	 *
	 * @since 4.0.0
	 *
	 * @return View\Helper\Messenger
	 */

	protected function _messengerFactory() : View\Helper\Messenger
	{
		return new View\Helper\Messenger($this->_registry);
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
		$messenger = $this->_messengerFactory();
		$route = $this->_request->getQuery('redirect') ? : 'admin';
		return $messenger
			->setRoute($this->_language->get('continue'), $route)
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
		$messenger = $this->_messengerFactory();
		return $messenger
			->setRoute($this->_language->get('back'), 'login')
			->error($this->_language->get('something_wrong'), $this->_language->get('error_occurred'));
	}

	/**
	 * reinstall
	 *
	 * @since 2.4.0
	 */

	protected function _reinstall() : void
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

		foreach ($this->_optionArray['modules'] as $key => $moduleClass)
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
