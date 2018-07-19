<?php
namespace Redaxscript\Module;

use Redaxscript\Config;
use Redaxscript\Installer;
use Redaxscript\Language;
use Redaxscript\Model;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * parent class to create a module
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

class Module
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
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = [];

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
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param array $moduleArray custom module setup
	 */

	public function init(array $moduleArray = [])
	{
		/* merge module setup */

		if (is_array($moduleArray))
		{
			static::$_moduleArray = array_merge(static::$_moduleArray, $moduleArray);
		}

		/* load the language */

		if (is_array(static::$_moduleArray) && array_key_exists('alias', static::$_moduleArray))
		{
			$this->_language->load(
			[
				'modules' . DIRECTORY_SEPARATOR . static::$_moduleArray['alias'] . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'en.json',
				'modules' . DIRECTORY_SEPARATOR . static::$_moduleArray['alias'] . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $this->_registry->get('language') . '.json'
			]);
		}
	}

	/**
	 * install the module
	 *
	 * @since 2.6.0
	 *
	 * @return bool
	 */

	public function install()
	{
		if (is_array(static::$_moduleArray) && array_key_exists('alias', static::$_moduleArray))
		{
			$moduleModel = new Model\Module();
			$moduleModel->createByArray(static::$_moduleArray);

			/* create from sql */

			$directory = 'modules' . DIRECTORY_SEPARATOR . static::$_moduleArray['alias'] . DIRECTORY_SEPARATOR . 'database';
			if (is_dir($directory))
			{
				$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
				$installer->init($directory);
				$installer->rawCreate();
			}
			$moduleModel->clearCache();
			return $moduleModel->query()->where('alias', static::$_moduleArray['alias'])->count() === 1;
		}
		return false;
	}

	/**
	 * uninstall the module
	 *
	 * @since 2.6.0
	 *
	 * @return bool
	 */

	public function uninstall()
	{
		if (is_array(static::$_moduleArray) && array_key_exists('alias', static::$_moduleArray))
		{
			$moduleModel = new Model\Module();
			$moduleModel->deleteByAlias(static::$_moduleArray['alias']);

			/* drop from sql */

			$directory = 'modules' . DIRECTORY_SEPARATOR . static::$_moduleArray['alias'] . DIRECTORY_SEPARATOR . 'database';
			if (is_dir($directory))
			{
				$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
				$installer->init($directory);
				$installer->rawDrop();
			}
			$moduleModel->clearCache();
			return $moduleModel->query()->where('alias', static::$_moduleArray['alias'])->count() === 0;
		}
		return false;
	}
}