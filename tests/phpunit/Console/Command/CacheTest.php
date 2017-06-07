<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CacheTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class CacheTest extends TestCaseAbstract
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

		$cacheCommand = new Command\Cache($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $cacheCommand->getHelp();
		$actual = $cacheCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testClear
	 *
	 * @since 3.0.0
	 */

	public function testClear()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'cache',
			'clear',
			'--directory',
			'cache',
			'--extension',
			'css',
			'--bundle',
			'base.min.css'
		]);
		$cacheCommand = new Command\Cache($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $cacheCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testClearFailure
	 *
	 * @since 3.0.0
	 */

	public function testClearFailure()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'cache',
			'clear',
			'--no-interaction'
		]);
		$cacheCommand = new Command\Cache($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $cacheCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testClearInvalid
	 *
	 * @since 3.0.0
	 */

	public function testClearInvalid()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'cache',
			'clear-invalid',
			'--directory',
			'cache',
			'--extension',
			'js',
			'--lifetime',
			'3600'
		]);
		$cacheCommand = new Command\Cache($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $cacheCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testClearInvalidFailure
	 *
	 * @since 3.0.0
	 */

	public function testClearInvalidFailure()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'cache',
			'clear-invalid',
			'--no-interaction'
		]);
		$cacheCommand = new Command\Cache($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $cacheCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
