<?php
namespace Redaxscript\Tests\Console\Command;

use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Console\Command;
use Redaxscript\Tests\TestCaseAbstract;

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
 * @requires PHP 7.2
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
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		Stream::setup('root', 0777, $this->getJSON('tests' . DIRECTORY_SEPARATOR . 'provider' . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'ConsoleTest_setUp.json'));
	}

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

		/* expect and actual */

		if ($this->_config->get('dbType') === 'sqlite')
		{
			$this->markTestSkipped();
		}
		$expect = $backupCommand->success();
		$actual = $backupCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
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

		/* expect and actual */

		$expect = $backupCommand->error();
		$actual = $backupCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
