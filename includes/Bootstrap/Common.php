<?php
namespace Redaxscript\Bootstrap;

use PDO;
use Redaxscript\Client;
use Redaxscript\Server;
use function getenv;
use function is_dir;
use function strpos;
use function strtolower;
use function version_compare;

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

	public function autorun() : void
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

	protected function _setServer() : void
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

	protected function _setClient() : void
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

	protected function _setDriver() : void
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

	protected function _setModule() : void
	{
		$this->_registry->set('moduleArray',
		[
			'mod_brotli' => getenv('REDIRECT_MOD_BROTLI') === 'on',
			'mod_deflate' => getenv('REDIRECT_MOD_DEFLATE') === 'on',
			'mod_security' => getenv('REDIRECT_MOD_SECURITY') === 'on',
			'mod_rewrite' => getenv('REDIRECT_MOD_REWRITE') === 'on',
			'mod_headers' => getenv('REDIRECT_MOD_HEADERS') === 'on'
		]);
	}

	/**
	 * set the php
	 *
	 * @since 4.3.0
	 */

	protected function _setPhp() : void
	{
		$phpOs = strtolower(PHP_OS);
		$phpVersion = PHP_VERSION;
		if (strpos($phpOs, 'linux') === 0)
		{
			$this->_registry->set('phpOs', 'linux');
		}
		else if (strpos($phpOs, 'win') === 0)
		{
			$this->_registry->set('phpOs', 'windows');
		}
		$this->_registry->set('phpVersion', $phpVersion);
		$this->_registry->set('phpStatus', 0);
		if (version_compare($phpVersion, '7.5', '>='))
		{
			$this->_registry->set('phpStatus', 1);
		}
		else if (version_compare($phpVersion, '7.2', '>='))
		{
			$this->_registry->set('phpStatus', 2);
		}
	}
}
