<?php
namespace Redaxscript\Console;

use Redaxscript\Config;
use Redaxscript\Request;

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
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * array of namespaces
	 *
	 * @var string
	 */

	protected $_namespaceArray = array(
		'config' => 'Redaxscript\Console\Command\Config',
		'help' => 'Redaxscript\Console\Command\Help',
		'install' => 'Redaxscript\Console\Command\Install',
		'setting' => 'Redaxscript\Console\Command\Setting',
		'status' => 'Redaxscript\Console\Command\Status',
		'uninstall' => 'Redaxscript\Console\Command\Uninstall'
	);

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Config $config instance of the config class
	 * @param Request $request instance of the request class
	 */

	public function __construct(Config $config, Request $request)
	{
		$this->_config = $config;
		$this->_request = $request;
	}
}
