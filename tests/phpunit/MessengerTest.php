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
 */

class MessengerTest extends TestCaseAbstract
{
	/**
	 * providerSuccess
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerSuccess() : array
	{
		return $this->getProvider('tests/provider/messenger_success.json');
	}

	/**
	 * providerInfo
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerInfo() : array
	{
		return $this->getProvider('tests/provider/messenger_info.json');
	}

	/**
	 * providerWarning
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerWarning() : array
	{
		return $this->getProvider('tests/provider/messenger_warning.json');
	}

	/**
	 * providerError
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerError() : array
	{
		return $this->getProvider('tests/provider/messenger_error.json');
	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/messenger_render.json');
	}

	/**
	 * testSuccess
	 *
	 * @since 3.0.0
	 *
	 * @param array $success
	 * @param array $actionArray
	 * @param string $expect
	 *
	 * @dataProvider providerSuccess
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
	 * @dataProvider providerInfo
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
	 * @dataProvider providerWarning
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
	 * @dataProvider providerError
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
	 * @dataProvider providerRender
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
