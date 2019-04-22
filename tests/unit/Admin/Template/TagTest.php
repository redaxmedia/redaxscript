<?php
namespace Redaxscript\Tests\Admin\Template;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TagTest
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\Template\Tag
 */

class TagTest extends TestCaseAbstract
{
	/**
	 * testPanel
	 *
	 * @since 3.0.0
	 */

	public function testPanel() : void
	{
		/* actual */

		$actual = Admin\Template\Tag::panel();

		/* compare */

		$this->assertIsString($actual);
	}
}
