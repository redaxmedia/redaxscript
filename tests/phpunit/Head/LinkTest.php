<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Head;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CommentTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class LinkTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var \Redaxscript\Registry
	 */

	protected $_registry;
	/**
	 * instance of the language class
	 *
	 * @var \Redaxscript\Language
	 */

	protected $_language;
	/**
	 * instance of the request class
	 *
	 * @var \Redaxscript\Request
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
		$this->_request = Request::getInstance();
	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/Head/link_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param string $href
	 * @param string $rel
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($href = null, $rel = null, $expect = null)
	{
		/* setup */

		$linkHead = new Head\Link($this->_registry, $this->_language, $this->_request);
		$linkHead->append($href, $rel);

		/* actual */

		$actual = $linkHead;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}