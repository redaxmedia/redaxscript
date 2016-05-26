<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CommentFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class CommentFormTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
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
		return $this->getProvider('tests/provider/Admin/View/comment_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param integer $commentId
	 * @param array $expectArray
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($registryArray = array(), $commentId = null, $expectArray = array())
	{
		/* setup */

		$this->_registry->init($registryArray);
		$commentForm = new Admin\View\CommentForm($this->_registry, $this->_language);

		/* actual */

		$actual = $commentForm->render($commentId);

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
