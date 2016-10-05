<?php
namespace Redaxscript\Controller;

use Redaxscript\Auth;
use Redaxscript\Messenger;

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

	public function process()
	{
		$auth = new Auth($this->_request);
		$auth->init();

		/* handle success */

		if ($auth->logout())
		{
			return $this->_success();
		}
		return $this->_error();
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _success()
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), 'login')
			->doRedirect(0)
			->success($this->_language->get('logged_out'), $this->_language->get('goodbye'));
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _error()
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'login')
			->error($this->_language->get('something_wrong'), $this->_language->get('error_occurred'));
	}
}