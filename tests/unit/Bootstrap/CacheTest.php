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
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 * @covers Redaxscript\Bootstrap\Cache
 *
 * @runTestsInSeparateProcesses
 */

class CacheTest extends TestCaseAbstract
{
	/**
	 * testCache
	 *
	 * @since 3.1.0
	 *
	 * @param array $registryArray
	 * @param array $queryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCache(array $registryArray = [], array $queryArray = [], array $expectArray = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->setQuery('no-cache', $queryArray['no-cache']);
		new Bootstrap\Cache($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'noAssetCache' => $this->_registry->get('noAssetCache'),
			'noPageCache' => $this->_registry->get('noAssetCache')
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
