<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SearchTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Search
 */

class SearchTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testCreateColumnArray
	 *
	 * @since 4.0.0
	 *
	 * @param string $table
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreateColumnArray(string $table = null, array $expectArray = []) : void
	{
		/* setup */

		$searchModel = new Model\Search();

		/* actual */

		$actualArray = $this->callMethod($searchModel, '_createColumnArray',
		[
			$table
		]);

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}


	/**
	 * testCreateLikeArray
	 *
	 * @since 4.0.0
	 *
	 * @param string $table
	 * @param string $search
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreateLikeArray(string $table = null, string $search = null, array $expectArray = []) : void
	{
		/* setup */

		$searchModel = new Model\Search();

		/* actual */

		$actualArray = $this->callMethod($searchModel, '_createLikeArray',
		[
			$table,
			$search
		]);

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
