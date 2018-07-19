<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * SearchFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\View\SearchForm
 * @covers Redaxscript\View\ViewAbstract
 */

class SearchFormTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param string $table
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(string $table = null, array $expectArray = [])
	{
		/* setup */

		$searchForm = new View\SearchForm($this->_registry, $this->_language);

		/* actual */

		$actual = $searchForm->render($table);

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
