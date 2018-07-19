<?php
namespace Redaxscript\Tests\Filesystem;

use Redaxscript\Filesystem;
use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CacheTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filesystem\Cache
 */

class CacheTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		Stream::setup('root', 0777);
	}

	/**
	 * testStore
	 *
	 * @since 3.0.0
	 *
	 * @param array $bundleArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testStore(array $bundleArray = [], array $expectArray = [])
	{
		/* setup */

		$cache = new Filesystem\Cache();
		$cache->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'test'), 'cache');

		/* process bundle */

		foreach ($bundleArray as $key => $value)
		{
			$cache->store($value, $key);
		}

		/* actual */

		$actualArray = scandir(Stream::url('root' . DIRECTORY_SEPARATOR . 'test'));

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testRetrieve
	 *
	 * @since 3.0.0
	 */

	public function testRetrieve()
	{
		/* setup */

		$cache = new Filesystem\Cache();
		$cache
			->init(Stream::url('root'), 'cache')
			->store('bundle-one', 'Content One');

		/* actual */

		$actual = $cache->retrieve('bundle-one');

		/* compare */

		$this->assertEquals('Content One', $actual);
	}

	/**
	 * testRetrieveInvalid
	 *
	 * @since 3.0.0
	 */

	public function testRetrieveInvalid()
	{
		/* setup */

		$cache = new Filesystem\Cache();
		$cache->init(Stream::url('root'), 'cache');

		/* actual */

		$actual = $cache->retrieve('invalid');

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testValidate
	 *
	 * @since 3.0.0
	 */

	public function testValidate()
	{
		/* setup */

		$cache = new Filesystem\Cache();
		$cache
			->init(Stream::url('root'), 'cache')
			->store('bundle-one', 'Content One')
			->store('bundle-two', 'Content Two');

		/* lifetime */

		touch($cache->getPath('bundle-two'), time() - 3600);

		/* compare */

		$this->assertTrue($cache->validate('bundle-one'));
		$this->assertFalse($cache->validate('bundle-two'));
		$this->assertFalse($cache->validate('invalid'));
	}

	/**
	 * testClear
	 *
	 * @since 3.0.0
	 */

	public function testClear()
	{
		/* setup */

		$cache = new Filesystem\Cache();
		$cache
			->init(Stream::url('root'), 'cache')
			->store('bundle-one', 'Content One')
			->store('bundle-two', 'Content Two')
			->clear()
			->store('bundle-three', 'Content Three')
			->store('bundle-four', 'Content Four')
			->clear('bundle-three');

		/* compare */

		$this->assertFalse(is_file($cache->getPath('bundle-one')));
		$this->assertFalse(is_file($cache->getPath('bundle-two')));
		$this->assertFalse(is_file($cache->getPath('bundle-three')));
		$this->assertTrue(is_file($cache->getPath('bundle-four')));
	}

	/**
	 * testClearInvalid
	 *
	 * @since 3.0.0
	 */

	public function testClearInvalid()
	{
		/* setup */

		$cache = new Filesystem\Cache();
		$cache
			->init(Stream::url('root'), 'cache')
			->store('bundle-one', 'Content One')
			->store('bundle-two', 'Content Two')
			->store('bundle-three', 'Content Three')
			->store('bundle-four', 'Content Four');

		/* lifetime */

		touch($cache->getPath('bundle-one'), time() - 3600);
		touch($cache->getPath('bundle-two'), time() - 3600);
		touch($cache->getPath('bundle-three'), time() - 3600);
		$cache->clearInvalid();

		/* compare */

		$this->assertFalse(is_file($cache->getPath('bundle-one')));
		$this->assertFalse(is_file($cache->getPath('bundle-two')));
		$this->assertFalse(is_file($cache->getPath('bundle-three')));
		$this->assertTrue(is_file($cache->getPath('bundle-four')));
	}
}
