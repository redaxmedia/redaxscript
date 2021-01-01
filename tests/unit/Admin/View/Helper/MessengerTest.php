<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * MessengerTest
 *
 * @since 4.5.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\Helper\Messenger
 */

class MessengerTest extends TestCaseAbstract
{
	/**
	 * testSuccess
	 *
	 * @since 4.5.0
	 *
	 * @param array $successArray
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSuccess(array $successArray = [], array $actionArray = [], string $expect = null) : void
	{
		/* setup */

		$messenger = new Admin\View\Helper\Messenger($this->_registry);
		$messenger->setRoute($actionArray['text'], $actionArray['route']);

		/* actual */

		$actual = $messenger->success($successArray['message'], $successArray['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInfo
	 *
	 * @since 4.5.0
	 *
	 * @param array $infoArray
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInfo(array $infoArray = [], array $actionArray = [], string $expect = null) : void
	{
		/* setup */

		$messenger = new Admin\View\Helper\Messenger($this->_registry);
		$messenger->setRoute($actionArray['text'], $actionArray['route']);

		/* actual */

		$actual = $messenger->info($infoArray['message'], $infoArray['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testWarning
	 *
	 * @since 4.5.0
	 *
	 * @param array $warningArray
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testWarning(array $warningArray = [], array $actionArray = [], string $expect = null) : void
	{
		/* setup */

		$messenger = new Admin\View\Helper\Messenger($this->_registry);
		$messenger->setRoute($actionArray['text'], $actionArray['route']);

		/* actual */

		$actual = $messenger->warning($warningArray['message'], $warningArray['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testError
	 *
	 * @since 4.5.0
	 *
	 * @param array $errorArray
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testError(array $errorArray = [], array $actionArray = [], string $expect = null) : void
	{
		/* setup */

		$messenger = new Admin\View\Helper\Messenger($this->_registry);
		$messenger->setRoute($actionArray['text'], $actionArray['route']);

		/* actual */

		$actual = $messenger->error($errorArray['message'], $errorArray['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRender
	 *
	 * @since 4.5.0
	 *
	 * @param array $renderArray
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $renderArray = [], array $actionArray = [], string $expect = null) : void
	{
		/* setup */

		$messenger = new Admin\View\Helper\Messenger($this->_registry);
		$messenger->init();
		$messenger
			->setUrl($actionArray['text'], $actionArray['url'])
			->doRedirect($actionArray['timeout']);

		/* actual */

		$actual = $messenger->render($renderArray['type'], $renderArray['message'], $renderArray['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
