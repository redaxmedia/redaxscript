<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Language;
use Redaxscript\Messenger;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * abstract class to create a controller class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

abstract class ControllerAbstract implements ControllerInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected $_config;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 * @param Language $language instance of the language class
	 * @param Config $config instance of the config class
	 */

	public function __construct(Registry $registry, Request $request, Language $language, Config $config)
	{
		$this->_registry = $registry;
		$this->_request = $request;
		$this->_language = $language;
		$this->_config = $config;
	}

	/**
	 * normalize the post
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _normalizePost(array $postArray = [])
	{
		return array_map(function($value)
		{
			return $value === 'select' || !strlen($value) ? null : $value;
		}, $postArray);
	}

	/**
	 * show the success
	 *
	 * @since 4.0.0
	 *
	 * @param array $successArray array of the success
	 *
	 * @return string
	 */

	protected function _success(array $successArray = []) : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), $successArray['route'])
			->doRedirect($successArray['timeout'])
			->success($successArray['message'], $successArray['title'] ? $successArray['title'] : $this->_language->get('operation_completed'));
	}

	/**
	 * show the info
	 *
	 * @since 4.0.0
	 *
	 * @param array $infoArray array of the info
	 *
	 * @return string
	 */

	protected function _info(array $infoArray = []) : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), $infoArray['route'])
			->doRedirect($infoArray['timeout'])
			->warning($infoArray['message'], $infoArray['title']);
	}

	/**
	 * show the warning
	 *
	 * @since 4.0.0
	 *
	 * @param array $warningArray array of the warning
	 *
	 * @return string
	 */

	protected function _warning(array $warningArray = []) : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), $warningArray['route'])
			->doRedirect($warningArray['timeout'])
			->warning($warningArray['message'], $warningArray['title'] ? $warningArray['title'] : $this->_language->get('operation_completed'));
	}

	/**
	 * show the error
	 *
	 * @since 4.0.0
	 *
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	protected function _error(array $errorArray = []) : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), $errorArray['route'])
			->error($errorArray['message'], $errorArray['title'] ? $errorArray['title'] : $this->_language->get('error_occurred'));
	}
}
