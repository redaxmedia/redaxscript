<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * InstallTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class InstallTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * array to restore config
	 *
	 * @var array
	 */

	protected $_configArray = [];

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_request = Request::getInstance();
		$this->_config = Config::getInstance();
		$this->_configArray = $this->_config->get();
		$this->_config->set('dbPrefix', 'console_');
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$this->_request->setServer('argv', null);
		$this->_config->set('dbPrefix', $this->_configArray['dbPrefix']);
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument()
	{
		/* setup */

		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_config);

		/* expect and actual */

		$expect = $installCommand->getHelp();
		$actual = $installCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testDatabase
	 *
	 * @since 3.0.0
	 */

	public function testDatabase()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'install',
			'database',
			'--admin-name',
			'test',
			'--admin-user',
			'test',
			'--admin-password',
			'test',
			'--admin-email',
			'test@test.com'
		]);
		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $installCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testDatabaseInvalid
	 *
	 * @since 3.0.0
	 */

	public function testDatabaseInvalid()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'install',
			'database',
			'--no-interaction'
		]);
		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $installCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testModule
	 *
	 * @since 3.0.0
	 */

	public function testModule()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'install',
			'module',
			'--alias',
			'TestDummy'
		]);
		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $installCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testModule
	 *
	 * @since 3.0.0
	 */

	public function testModuleInvalid()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'install',
			'module',
			'--no-interaction'
		]);
		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $installCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
