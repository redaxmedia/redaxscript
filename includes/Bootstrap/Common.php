<?php
namespace Redaxscript\Bootstrap;

use PDO;
use Redaxscript\Client;
use Redaxscript\Server;

/**
 * children class to boot the common
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Common extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	protected function _autorun()
	{
		$this->_setServer();
		$this->_setClient();
		$this->_setDriver();
		$this->_setModule();
		$this->_setPhp();
	}

	/**
	 * set the server
	 *
	 * @since 3.1.0
	 */

	protected function _setServer()
	{
		$file = new Server\File($this->_request);
		$root = new Server\Root($this->_request);
		$host = new Server\Host($this->_request);
		$token = new Server\Token($this->_request);

		/* set the registry */

		$this->_registry->set('file', $file->getOutput());
		$this->_registry->set('root', $root->getOutput());
		$this->_registry->set('host', $host->getOutput());
		$this->_registry->set('token', $token->getOutput());
	}

	/**
	 * set the client
	 *
	 * @since 3.1.0
	 */

	protected function _setClient()
	{
		$browser = new Client\Browser($this->_request);
		$version = new Client\Version($this->_request);
		$engine = new Client\Engine($this->_request);
		$mobile = new Client\Mobile($this->_request);
		$tablet = new Client\Tablet($this->_request);
		$desktop = new Client\Desktop($this->_request);

		/* set the registry */

		$this->_registry->set('myBrowser', $browser->getOutput());
		$this->_registry->set('myBrowserVersion', $version->getOutput());
		$this->_registry->set('myEngine', $engine->getOutput());
		$this->_registry->set('myMobile', $mobile->getOutput());
		$this->_registry->set('myTablet', $tablet->getOutput());
		$this->_registry->set('myDesktop', $desktop->getOutput());
	}

	/**
	 * set the driver
	 *
	 * @since 3.1.0
	 */

	protected function _setDriver()
	{
		$driverArray = [];

		/* process driver */

		foreach (PDO::getAvailableDrivers() as $driver)
		{
			$driver = $driver === 'sqlsrv' ? 'mssql' : $driver;
			if (is_dir('database' . DIRECTORY_SEPARATOR . $driver))
			{
				$driverArray[$driver] = $driver;
			}
		}
		$this->_registry->set('driverArray', $driverArray);
	}

	/**
	 * set the module
	 *
	 * @since 3.1.0
	 */

	protected function _setModule()
	{
		$moduleArray = function_exists('apache_get_modules') ? apache_get_modules() : [];
		$fallbackArray =
		[
			'mod_deflate',
			'mod_headers',
			'mod_rewrite'
		];

		/* process fallback */

		if (!$moduleArray)
		{
			foreach ($fallbackArray as $value)
			{
				if (getenv('REDIRECT_' . $value) === 'on')
				{
					$moduleArray[$value] = true;
				}
			}
		}
		$this->_registry->set('moduleArray', $moduleArray);
	}

	/**
	 * set the php
	 *
	 * @since 3.2.3
	 */

	protected function _setPhp()
	{
		$phpOs = strtolower(php_uname('s'));
		$phpVersion = phpversion();
		if (substr($phpOs, 0, 5) === 'linux')
		{
			$this->_registry->set('phpOs', 'linux');
		}
		else if (substr($phpOs, 0, 3) === 'win')
		{
			$this->_registry->set('phpOs', 'windows');
		}
		$this->_registry->set('phpVersion', $phpVersion);
		$this->_registry->set('phpStatus', version_compare($phpVersion, '7.0', '>='));

	}
}
