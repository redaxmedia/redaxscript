<?php
namespace Redaxscript\Tests\Console;

use Redaxscript\Console;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ConsoleTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Console\Console
 * @covers Redaxscript\Console\ConsoleAbstract
 */

class ConsoleTest extends TestCaseAbstract
{
	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown() : void
	{
		$this->_request->setServer('argv', null);
		$this->_request->setPost('argv', null);
	}

	/**
	 * testCli
	 *
	 * @since 3.0.0
	 */

	public function testCli() : void
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'help'
		]);
		$console = new Console\Console($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $console->init('cli');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testTemplate
	 *
	 * @since 3.0.0
	 */

	public function testTemplate() : void
	{
		/* setup */

		$this->_request->setPost('argv', 'help');
		$console = new Console\Console($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $console->init('template');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testInvalid
	 *
	 * @since 3.0.0
	 */

	public function testInvalid() : void
	{
		/* setup */

		$console = new Console\Console($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $console->init();

		/* compare */

		$this->assertNull($actual);
	}
}
