<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Installer;
use Redaxscript\Language;
use Redaxscript\Registry;
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
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

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
		$this->_language = Language::getInstance();
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
		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$installer->rawDrop();
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

		$uninstallCommand = new Command\Uninstall($this->_registry, $this->_request, $this->_language, $this->_config);

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

		$this->_request->setServer('argv',
		[
			'console.php',
			'uninstall',
			'database'
		]);
		$uninstallCommand = new Command\Uninstall($this->_registry, $this->_request, $this->_language, $this->_config);

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

		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$installer->rawCreate();
		$this->_request->setServer('argv',
		[
			'console.php',
			'uninstall',
			'module',
			'--alias',
			'TestDummy'
		]);
		$uninstallCommand = new Command\Uninstall($this->_registry, $this->_request, $this->_language, $this->_config);

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

	public function testModuleInvalid()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'uninstall',
			'module',
			'--no-interaction'
		]);
		$uninstallCommand = new Command\Uninstall($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $uninstallCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
