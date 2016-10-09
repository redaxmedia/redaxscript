<?php
namespace Redaxscript\Tests;

use Redaxscript\Cache;
use org\bovigo\vfs\vfsStream as Stream;

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
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		Stream::setup('root', 0777);
	}

	/**
	 * providerStore
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerStore()
	{
		return $this->getProvider('tests/provider/cache_store.json');
	}

	/**
	 * testStore
	 *
	 * @since 3.0.0
	 *
	 * @param array $bundleArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerStore
	 */

	public function testStore($bundleArray = [], $expectArray = [])
	{
		/* setup */

		$cache = new Cache();
		$cache->init(Stream::url('root/test'));
		foreach ($bundleArray as $key => $value)
		{
			$cache->store($value, $key);
		}

		/* actual */

		$actualArray = scandir(Stream::url('root/test'));

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

		$cache = new Cache();
		$cache
			->init(Stream::url('root'))
			->store('test', 'test');

		/* actual */

		$actual = $cache->retrieve('test');

		/* compare */

		$this->assertEquals($actual, 'test');
	}

	/**
	 * testRetrieveInvalid
	 *
	 * @since 3.0.0
	 */

	public function testRetrieveInvalid()
	{
		/* setup */

		$cache = new Cache();
		$cache->init(Stream::url('root'));

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

		$cache = new Cache();
		$cache
			->init(Stream::url('root'))
			->store('test1', 'test')
			->store('test2', 'test');
		touch($cache->getPath('test2'), time() - 3600);

		/* compare */

		$this->assertTrue($cache->validate('test1'));
		$this->assertFalse($cache->validate('test2'));
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

		$cache = new Cache();
		$cache
			->init(Stream::url('root'))
			->store('test1', 'test')
			->store('test2', 'test')
			->clear()
			->store('test3', 'test')
			->store('test4', 'test')
			->clear('test3');

		/* compare */

		$this->assertFalse(file_exists($cache->getPath('test1')));
		$this->assertFalse(file_exists($cache->getPath('test2')));
		$this->assertFalse(file_exists($cache->getPath('test3')));
		$this->assertTrue(file_exists($cache->getPath('test4')));
	}

	/**
	 * testClearInvalid
	 *
	 * @since 3.0.0
	 */

	public function testClearInvalid()
	{
		/* setup */

		$cache = new Cache();
		$cache
			->init(Stream::url('root'))
			->store('test1', 'test')
			->store('test2', 'test')
			->store('test3', 'test')
			->store('test4', 'test');
		touch($cache->getPath('test1'), time() - 3600);
		touch($cache->getPath('test2'), time() - 3600);
		touch($cache->getPath('test3'), time() - 3600);
		$cache->clearInvalid();

		/* compare */

		$this->assertFalse(is_file($cache->getPath('test1')));
		$this->assertFalse(is_file($cache->getPath('test2')));
		$this->assertFalse(is_file($cache->getPath('test3')));
		$this->assertTrue(is_file($cache->getPath('test4')));
	}
}
