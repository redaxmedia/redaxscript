<?php
namespace Redaxscript\Controller;

use Redaxscript\Auth;

/**
 * children class to process the logout request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Logout extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process() : string
	{
		$auth = new Auth($this->_request);
		$auth->init();

		/* handle logout */

		if ($auth->logout())
		{
			return $this->_success(
			[
				'route' => 'login',
				'timeout' => 0,
				'message' => $this->_language->get('logged_out'),
				'title' => $this->_language->get('goodbye')
			]);
		}

		/* handle error */

		return $this->_error(
		[
			'route' => 'login',
			'message' => $this->_language->get('something_wrong')
		]);
	}
}