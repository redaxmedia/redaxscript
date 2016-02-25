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

class MessengerTest extends TestCase
{
	/**
	 * providerSuccess
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerSuccess()
	{
		return $this->getProvider('tests/provider/messenger_success.json');
	}

	/**
	 * providerWarning
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerWarning()
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

	public function providerError()
	{
		return $this->getProvider('tests/provider/messenger_error.json');
	}

	/**
	 * providerInfo
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerInfo()
	{
		return $this->getProvider('tests/provider/messenger_info.json');
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
		return $this->getProvider('tests/provider/messenger_render.json');
	}

	/**
	 * testSuccess
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerSuccess
	 *
	 * @param array $success
	 * @param array $action
	 * @param string $expect
	 */

	public function testSuccess($success = null, $action = null, $expect = null)
	{
		/* setup */

		$messenger = new Messenger();
		$messenger->setAction($action['text'], $action['route']);

		/* actual */

		$actual = $messenger->success($success['message'], $success['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testWarning
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerWarning
	 *
	 * @param array $warning
	 * @param array $action
	 * @param string $expect
	 */

	public function testWarning($warning = null, $action = null, $expect = null)
	{
		/* setup */

		$messenger = new Messenger();
		$messenger->setAction($action['text'], $action['route']);

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
	 * @dataProvider providerError
	 *
	 * @param array $error
	 * @param array $action
	 * @param string $expect
	 */

	public function testError($error = null, $action = null, $expect = null)
	{
		/* setup */

		$messenger = new Messenger();
		$messenger->setAction($action['text'], $action['route']);

		/* actual */

		$actual = $messenger->error($error['message'], $error['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInfo
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerInfo
	 *
	 * @param array $info
	 * @param array $action
	 * @param string $expect
	 */

	public function testInfo($info = null, $action = null, $expect = null)
	{
		/* setup */

		$messenger = new Messenger();
		$messenger->setAction($action['text'], $action['route']);

		/* actual */

		$actual = $messenger->info($info['message'], $info['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRender
	 *
	 * @param array $render
	 * @param array $action
	 * @param string $expect
	 */

	public function testRender($render = null, $action = null, $expect = null)
	{
		/* setup */

		$messenger = new Messenger();
		$messenger->setAction($action['text'], $action['route'])->doRedirect($action['timeout']);


		/* actual */

		$actual = $messenger->render($render['type'], $render['message'], $render['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
