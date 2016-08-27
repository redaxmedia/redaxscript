<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ScriptTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ScriptTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 3.0.0
	 */

	public function testRender()
	{
		/* setup */

		$scriptCore = Head\Script::getInstance();
		$scriptCore
			->append('src', 'assets/scripts/init.js')
			->append(
			[
				'src' => 'assets/scripts/misc.js',
				'async' => 'async'
			]);
		$scriptModule = Head\Script::getInstance();
		$scriptModule->append('src', 'modules/assets/scripts/init.js');

		/* actual */

		$actual = $scriptCore;

		/* compare */

		// TODO: please create another @dataprovider for this test - that internal one was just for rapid development

		$this->assertEquals('<script src="assets/scripts/init.js"></script><script src="assets/scripts/misc.js" async="async"></script><script src="modules/assets/scripts/init.js"></script>', $actual);
	}
}
