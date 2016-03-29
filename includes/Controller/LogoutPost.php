<?php
namespace Redaxscript\Controller;

use Redaxscript\Auth;
use Redaxscript\Language;
use Redaxscript\Messenger;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * children class to process logout
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class LogoutPost implements ControllerInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 * @param Request $request instance of the registry class
	 */

	public function __construct(Registry $registry, Language $language, Request $request)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		$this->_request = $request;
	}

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 */

	public function process()
	{
		$auth = new Auth($this->_request);
		$auth->init();

		/* handle error and success */

		if ($auth->logout())
		{
			return $this->success();
		}
		return $this->error();
	}

	/**
	 * show success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function success()
	{
		$messenger = new Messenger();
		return $messenger->setAction(Language::get('continue'), 'login')->doRedirect(0)->success(Language::get('logged_out'), Language::get('goodbye'));
	}

	/**
	 * show error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	public function error($errorArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction(Language::get('continue'), 'login')->error(Language::get('something_wrong'), Language::get('error_occurred'));
	}
}