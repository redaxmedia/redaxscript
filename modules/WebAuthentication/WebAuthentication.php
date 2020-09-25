<?php
namespace Redaxscript\Modules\WebAuthentication;

use Redaxscript\Controller;
use Redaxscript\Head;
use Redaxscript\Module;
use function sleep;

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
			// $this->_registry->set('routerBreak', true);

			// lookup if that user has login bouncer enabled inside modules_web_authentication
			// remember the post array and pass it to the webauthn/login endpoint via fetch()
			// do all the auth via key and if promise is fine then do the process login

			sleep(2);
			return $this->_processLogin();
		}
		return null;
	}

	/**
	 * process the login
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processLogin() : string
	{
		$loginController = new Controller\Login($this->_registry, $this->_request, $this->_language, $this->_config);
		return $loginController->process();
	}
}
