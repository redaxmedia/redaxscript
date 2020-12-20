<?php
namespace Redaxscript\Console;

use Redaxscript\Config;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;
use function php_sapi_name;

/**
 * abstract class to handle the command line interface
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

abstract class ConsoleAbstract
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
	 * array of command namespaces
	 *
	 * @var string
	 */

	protected $_namespaceArray;

	/**
	 * constructor of the class
	 *
	 * @since 4.5.0
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
		$accessValidator = new Validator\Access();

		if (php_sapi_name() === 'cli')
		{
			$this->_namespaceArray =
			[
				'backup' => 'Redaxscript\Console\Command\Backup',
				'cache' => 'Redaxscript\Console\Command\Cache',
				'config' => 'Redaxscript\Console\Command\Config',
				'help' => 'Redaxscript\Console\Command\Help',
				'install' => 'Redaxscript\Console\Command\Install',
				'migrate' => 'Redaxscript\Console\Command\Migrate',
				'restore' => 'Redaxscript\Console\Command\Restore',
				'setting' => 'Redaxscript\Console\Command\Setting',
				'status' => 'Redaxscript\Console\Command\Status',
				'uninstall' => 'Redaxscript\Console\Command\Uninstall'
			];
		}
		else if($this->_request->getServer('REMOTE_ADDR') === '127.0.0.1' || $accessValidator->validate('1', $registry->get('myGroups')))
		{
			$this->_namespaceArray =
			[
				'auth' => 'Redaxscript\Console\Command\Auth',
				'backup' => 'Redaxscript\Console\Command\Backup',
				'cache' => 'Redaxscript\Console\Command\Cache',
				'config' => 'Redaxscript\Console\Command\Config',
				'help' => 'Redaxscript\Console\Command\Help',
				'install' => 'Redaxscript\Console\Command\Install',
				'migrate' => 'Redaxscript\Console\Command\Migrate',
				'restore' => 'Redaxscript\Console\Command\Restore',
				'setting' => 'Redaxscript\Console\Command\Setting',
				'status' => 'Redaxscript\Console\Command\Status',
				'uninstall' => 'Redaxscript\Console\Command\Uninstall'
			];
		}
		else
		{
			$this->_namespaceArray =
			[
				'auth' => 'Redaxscript\Console\Command\Auth',
				'help' => 'Redaxscript\Console\Command\Help',
				'status' => 'Redaxscript\Console\Command\Status'
			];
		}
	}
}
