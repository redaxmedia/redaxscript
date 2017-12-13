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
 */

class SearchFormTest extends TestCaseAbstract
{
	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/View/search_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param string $table
	 * @param array $expectArray
	 *
	 * @dataProvider providerRender
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
