<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\View;
use function array_map;

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
	 * normalize the post
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array|null
	 */

	protected function _normalizePost(array $postArray = []) : ?array
	{
		return array_map(function($value)
		{
			return $value === 'null' || $value === '' ? null : $value;
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
		$messenger = $this->_messengerFactory();
		return $messenger
			->setRoute($this->_language->get('continue'), $successArray['route'])
			->doRedirect($successArray['timeout'])
			->success($successArray['message'] ? : $this->_language->get('operation_completed'), $successArray['title'] ? : $this->_language->get('success'));
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
		$messenger = $this->_messengerFactory();
		return $messenger
			->setRoute($this->_language->get('continue'), $infoArray['route'])
			->doRedirect($infoArray['timeout'])
			->warning($infoArray['message'] ? : $this->_language->get('something_wrong'), $infoArray['title'] ? : $this->_language->get('info'));
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
		$messenger = $this->_messengerFactory();
		return $messenger
			->setRoute($this->_language->get('continue'), $warningArray['route'])
			->doRedirect($warningArray['timeout'])
			->warning($warningArray['message'] ? : $this->_language->get('something_wrong'), $warningArray['title'] ? : $this->_language->get('warning'));
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
		$messenger = $this->_messengerFactory();
		return $messenger
			->setRoute($this->_language->get('back'), $errorArray['route'])
			->error($errorArray['message'] ? : $this->_language->get('something_wrong'), $errorArray['title'] ? : $this->_language->get('error'));
	}
}
