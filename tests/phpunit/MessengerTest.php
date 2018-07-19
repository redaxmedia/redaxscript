<?php
namespace Redaxscript\Tests;

use Redaxscript\Messenger;

/**
 * MessengerTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Messenger
 */

class MessengerTest extends TestCaseAbstract
{
	/**
	 * testSuccess
	 *
	 * @since 3.0.0
	 *
	 * @param array $success
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSuccess($success = null, $actionArray = [], string $expect = null)
	{
		/* setup */

		$messenger = new Messenger($this->_registry);
		$messenger->setRoute($actionArray['text'], $actionArray['route']);

		/* actual */

		$actual = $messenger->success($success['message'], $success['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInfo
	 *
	 * @since 3.0.0
	 *
	 * @param array $info
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInfo($info = null, $actionArray = [], string $expect = null)
	{
		/* setup */

		$messenger = new Messenger($this->_registry);
		$messenger->setRoute($actionArray['text'], $actionArray['route']);

		/* actual */

		$actual = $messenger->info($info['message'], $info['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testWarning
	 *
	 * @since 3.0.0
	 *
	 * @param array $warning
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testWarning($warning = null, $actionArray = [], string $expect = null)
	{
		/* setup */

		$messenger = new Messenger($this->_registry);
		$messenger->setRoute($actionArray['text'], $actionArray['route']);

		/* actual */

		$actual = $messenger->warning($warning['message'], $warning['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testError
	 *
	 * @since 3.0.0
	 *
	 * @param array $error
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testError($error = null, $actionArray = [], string $expect = null)
	{
		/* setup */

		$messenger = new Messenger($this->_registry);
		$messenger->setRoute($actionArray['text'], $actionArray['route']);

		/* actual */

		$actual = $messenger->error($error['message'], $error['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $render
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender($render = null, $actionArray = [], string $expect = null)
	{
		/* setup */

		$messenger = new Messenger($this->_registry);
		$messenger->init();
		$messenger
			->setUrl($actionArray['text'], $actionArray['url'])
			->doRedirect($actionArray['timeout']);

		/* actual */

		$actual = $messenger->render($render['type'], $render['message'], $render['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
