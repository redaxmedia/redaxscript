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
 */

class TagTest extends TestCaseAbstract
{
	/**
	 * testPanel
	 *
	 * @since 3.0.0
	 */

	public function testPanel()
	{
		/* actual */

		$actual = Admin\Template\Tag::panel();

		/* compare */

		$this->assertString($actual);
	}
}
