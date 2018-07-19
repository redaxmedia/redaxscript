<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * InstallTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Console\Command\CommandAbstract
 * @covers Redaxscript\Console\Command\Install
 */

class InstallTest extends TestCaseAbstract
{
	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
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

		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_language, $this->_config);

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
		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_language, $this->_config);

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
		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_language, $this->_config);

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

		$this->createDatabase();
		$this->_request->setServer('argv',
		[
			'console.php',
			'install',
			'module',
			'--alias',
			'TestDummy'
		]);
		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $installCommand->run('cli');

		/* teardown */

		$this->uninstallTestDummy();

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

		$this->createDatabase();
		$this->_request->setServer('argv',
		[
			'console.php',
			'install',
			'module',
			'--no-interaction'
		]);
		$installCommand = new Command\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $installCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
