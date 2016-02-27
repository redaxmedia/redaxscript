<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Tests\TestCase;
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

class SearchFormTest extends TestCase
{
	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/View/search_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param string $table
	 * @param array $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($table = null, $expect = array())
	{
		/* setup */

		$searchForm = new View\SearchForm();

		/* actual */

		$actual = $searchForm->render($table);

		/* compare */

		$this->assertStringStartsWith($expect['start'], $actual);
		$this->assertStringEndsWith($expect['end'], $actual);
	}
}
