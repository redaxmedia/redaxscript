<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Tests\TestCaseAbstract;
use org\bovigo\vfs\vfsStream as Stream;

/**
 * RestoreTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
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

	public function setUp()
	{
		parent::setUp();
		Stream::setup('root', 0777, $this->getProvider('tests/provider/Console/console_setup.json'));
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

	public function testDatabase()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'restore',
			'database',
			'--directory',
			Stream::url('root/build'),
			'--file',
			'test.sql'
		]);
		$restoreCommand = new Command\Restore($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $restoreCommand->run('cli');

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
			'restore',
			'database',
			'--no-interaction'
		]);
		$restoreCommand = new Command\Restore($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $restoreCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
