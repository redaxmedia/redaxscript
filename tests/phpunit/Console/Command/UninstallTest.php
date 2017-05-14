<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Modules\TestDummy;
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
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
		$this->_request->setServer('argv', null);
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

		$testDummy = new TestDummy\TestDummy($this->_registry, $this->_request, $this->_language, $this->_config);
		$testDummy->install();
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
