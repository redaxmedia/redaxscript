<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Tests\TestCaseAbstract;
use org\bovigo\vfs\vfsStream as Stream;

/**
 * BackupTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Console\Command\Backup
 * @covers Redaxscript\Console\Command\CommandAbstract
 *
 * @requires OS Linux
 */

class BackupTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.2.0
	 */

	public function setUp()
	{
		parent::setUp();
		Stream::setup('root', 0777, $this->getJSON('tests' . DIRECTORY_SEPARATOR . 'provider' . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'ConsoleTest_setUp.json'));
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
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

		$backupCommand = new Command\Backup($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $backupCommand->getHelp();
		$actual = $backupCommand->run('cli');

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
			'backup',
			'database',
			'--directory',
			Stream::url('root' . DIRECTORY_SEPARATOR . 'build')
		]);
		$backupCommand = new Command\Backup($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $backupCommand->run('cli');

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
			'backup',
			'database',
			'--no-interaction'
		]);
		$backupCommand = new Command\Backup($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $backupCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
