<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Installer;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * UninstallTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class UninstallTest extends TestCaseAbstract
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
	 * array to restore config
	 *
	 * @var array
	 */

	protected $_configArray = array();

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
		$this->_request = Request::getInstance();
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

		$uninstallCommand = new Command\Uninstall($this->_config, $this->_request);

		/* expect and actual */

		$expect = $uninstallCommand->getHelp();
		$actual = $uninstallCommand->run('cli');

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

		$this->_request->setServer('argv', array(
			'console.php',
			'uninstall',
			'database'
		));
		$uninstallCommand = new Command\Uninstall($this->_config, $this->_request);

		/* actual */

		$actual = $uninstallCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testModule
	 *
	 * @since 3.0.0
	 */

	public function testModule()
	{
		/* setup */

		$installer = new Installer($this->_config);
		$installer->init();
		$installer->rawCreate();
		$this->_request->setServer('argv', array(
			'console.php',
			'uninstall',
			'module',
			'--alias',
			'TestDummy'
		));
		$uninstallCommand = new Command\Uninstall($this->_config, $this->_request);

		/* actual */

		$actual = $uninstallCommand->run('cli');

		/* teardown */

		$installer->rawDrop();

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

		$this->_request->setServer('argv', array(
			'console.php',
			'uninstall',
			'module',
			'--no-interaction'
		));
		$uninstallCommand = new Command\Uninstall($this->_config, $this->_request);

		/* actual */

		$actual = $uninstallCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
