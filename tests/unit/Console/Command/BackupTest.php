<?php
namespace Redaxscript\Tests\Console\Command;

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
 */

class BackupTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.2.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
		$this->_request->setServer('argv', null);
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument() : void
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

	public function testDatabase() : void
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'backup',
			'database',
			'--directory',
			'build'
		]);
		$backupCommand = new Command\Backup($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

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

	public function testDatabaseInvalid() : void
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
