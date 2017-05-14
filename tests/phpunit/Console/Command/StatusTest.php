<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * StatusTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class StatusTest extends TestCaseAbstract
{
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

		$statusCommand = new Command\Status($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $statusCommand->getHelp();
		$actual = $statusCommand->run('cli');

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
			'status',
			'database'
		]);
		$statusCommand = new Command\Status($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $statusCommand->run('cli');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testSystem
	 *
	 * @since 3.0.0
	 */

	public function testSystem()
	{
		/* setup */

		$this->_registry->set('moduleArray',
		[
			'mod_deflate',
			'mod_headers',
			'mod_rewrite'
		]);
		$this->_request->setServer('argv',
		[
			'console.php',
			'status',
			'system'
		]);
		$statusCommand = new Command\Status($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $statusCommand->run('cli');

		/* compare */

		$this->assertString($actual);
	}
}
