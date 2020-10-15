<?php
namespace Redaxscript\Modules\WebAuthentication;

use Redaxscript\Db;
use Redaxscript\Controller;
use Redaxscript\Head;

/**
 * integrate web authentication
 *
 * @since 4.5.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class WebAuthentication extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'WebAuthentication',
		'alias' => 'WebAuthentication',
		'author' => 'Redaxmedia',
		'description' => 'Integrate web authentication',
		'version' => '4.5.0',
		'license' => 'Sponsorware'
	];

	/**
	 * renderStart
	 *
	 * @since 4.5.0
	 */

	public function renderStart() : void
	{
		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				'modules/WebAuthentication/assets/scripts/init.js',
				'modules/WebAuthentication/dist/scripts/web-authentication.min.js'
			]);

		/* route as needed */

		// create for webauth
		// get for webauth
		// destroy to remove webauth from database
		// login to forward to backend
		// challenge for backend driven challenge

		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'web-authentication' && $this->_registry->get('tokenParameter'))
		{
			if ($this->_registry->get('thirdParameter') === 'login')
			{
				$this->_registry->set('renderBreak', true);
				$this->_processLogin();
			}
		}
	}

	/**
	 * routeContent
	 *
	 * @since 4.5.0
	 */

	public function routeContent() : ?string
	{
		if ($this->_request->getPost('Redaxscript\View\LoginForm'))
		{
			$isEnabled = Db::forTablePrefix('modules_web_authentication')
				->where(
				[
					'user' => $this->_request->getPost('user'),
					'status' => 1
				]);
			if ($isEnabled)
			{
				$this->_request->setSession('loginArray',
				[
					'user' => $this->_request->getPost('user'),
					'password' => $this->_request->getPost('password'),
					'task' => $this->_request->getPost('task'),
					'solution' => $this->_request->getPost('solution')
				]);
				return '<a href="/modules/web-authentication">trigger webauth</a>';
			}
		}
		return null;
	}

	/**
	 * process the login
	 *
	 * @since 4.5.0
	 *
	 * @return string
	 */

	protected function _processLogin() : string
	{
		$this->_request->set('post', $this->_request->getSession('loginArray'));
		$loginController = new Controller\Login($this->_registry, $this->_request, $this->_language, $this->_config);
		return $loginController->process();
	}
}
