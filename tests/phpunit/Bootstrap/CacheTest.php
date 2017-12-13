<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CacheTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @runTestsInSeparateProcesses
 */

class CacheTest extends TestCaseAbstract
{
	/**
	 * providerCache
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */

	public function providerCache() : array
	{
		return $this->getProvider('tests/provider/Bootstrap/cache.json');
	}

	/**
	 * testCache
	 *
	 * @since 3.1.0
	 *
	 * @param array $registryArray
	 * @param array $queryArray
	 * @param bool $expect
	 *
	 * @dataProvider providerCache
	 */

	public function testCache(array $registryArray = [], array $queryArray = [], bool $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->setQuery('no-cache', $queryArray['no-cache']);
		new Bootstrap\Cache($this->_registry, $this->_request);

		/* actual */

		$actual = $this->_registry->get('noCache');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
