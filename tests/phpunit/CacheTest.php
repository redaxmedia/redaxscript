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
		Stream::setup('root', 0777, $this->getProvider('tests/provider/cache_set_up.json'));
	}
}
