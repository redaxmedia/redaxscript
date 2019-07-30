<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CommentFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\CommentForm
 * @covers Redaxscript\Admin\View\ViewAbstract
 */

class CommentFormTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment One',
				'text' => 'comment-one',
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param int $commentId
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], int $commentId = null, array $expectArray = []) : void
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
