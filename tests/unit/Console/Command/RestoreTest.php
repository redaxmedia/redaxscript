<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Dater;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * RestoreTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Console\Command\CommandAbstract
 * @covers Redaxscript\Console\Command\Restore
 *
 * @requires OS Linux
 */

class RestoreTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.0.0
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

		$restoreCommand = new Command\Restore($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $restoreCommand->getHelp();
		$actual = $restoreCommand->run('cli');

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

		$dater = new Dater();
		$dater->init();
		$dbType = $this->_config->get('dbType');
		$dbName = $this->_config->get('dbName');
		$file = $dbName ? $dbName . '_' . $dater->formatDate() . '_' . $dater->formatTime() . '.' . $dbType : $dater->formatDate() . '_' . $dater->formatTime() . '.' . $dbType;
		$this->_request->setServer('argv',
		[
			'console.php',
			'restore',
			'database',
			'--directory',
			'build',
			'--file',
			$file
		]);
		$restoreCommand = new Command\Restore($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $restoreCommand->success();
		$actual = $restoreCommand->run('cli');

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
			'restore',
			'database',
			'--no-interaction'
		]);
		$restoreCommand = new Command\Restore($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $restoreCommand->error();
		$actual = $restoreCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
