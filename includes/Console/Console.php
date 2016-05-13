<?php
namespace Redaxscript\Console;

use Redaxscript\Config as BaseConfig;
use Redaxscript\Request as BaseRequest;

/**
 * parent class to handle the command line interface
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Console
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
		'backup' => 'Redaxscript\Console\Command\Backup',
		'config' => 'Redaxscript\Console\Command\Config',
		'help' => 'Redaxscript\Console\Command\Help',
		'install' => 'Redaxscript\Console\Command\Install',
		'status' => 'Redaxscript\Console\Command\Status',
		'setting' => 'Redaxscript\Console\Command\Setting',
		'uninstall' => 'Redaxscript\Console\Command\Uninstall'
	);

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param BaseConfig $config instance of the config class
	 * @param BaseRequest $request instance of the request class
	 */

	public function __construct(BaseConfig $config, BaseRequest $request)
	{
		$this->_config = $config;
		$this->_request = $request;
	}

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 */

	public function init()
	{
		$parser = new Parser($this->_request);
		$parser->init();

		/* run command */

		$commandKey = $parser->getArgument(1);
		if (!array_key_exists($commandKey, $this->_namespaceArray))
		{
			$commandKey = 'help';
		}
		$commandClass = $this->_namespaceArray[$commandKey];
		$command = new $commandClass($this->_config, $this->_request);
		return $command->run();
	}
}
