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
	 * providerRedirect
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRedirect()
	{
		return $this->getProvider('tests/provider/messenger_redirect.json');
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
	{
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRender
	 *
	 * @param array $info
	 * @param array $action
	 * @param string $expect
	 */

	public function testRender($info = null, $action = null, $expect = null)
	{
		$messenger = new Messenger();
		$messenger->setAction($action['action'], $action['route']);
		$actual = $messenger->render($info['data'], $info['type'], $info['title']);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * testSuccess
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerSuccess
	 *
	 * @param array $info
	 * @param array $action
	 * @param string $expect
	 */

	public function testSuccess($info = null, $action = null, $expect = null)
	{
		$messenger = new Messenger();
		$messenger->setAction($action['action'], $action['route']);
		$actual = $messenger->success($info['data'], $info['title']);
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
		$messenger = new Messenger();
		$messenger->setAction($action['action'], $action['route']);
		$actual = $messenger->info($info['data'], $info['title']);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * testWarning
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerWarning
	 *
	 * @param array $info
	 * @param array $action
	 * @param string $expect
	 */

	public function testWarning($info = null, $action = null, $expect = null)
	{
		$messenger = new Messenger();
		$messenger->setAction($action['action'], $action['route']);
		$actual = $messenger->warning($info['data'], $info['title']);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * testError
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerError
	 *
	 * @param array $info
	 * @param array $action
	 * @param string $expect
	 */

	public function testError($info = null, $action = null, $expect = null)
	{
		$messenger = new Messenger();
		$messenger->setAction($action['action'], $action['route']);
		$actual = $messenger->error($info['data'], $info['title']);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * tesRedirect
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRedirect
	 *
	 * @param array $datas
	 * @param array $action
	 * @param string $expect
	 */

	public function testRedirect($datas = null, $action = null, $expect = null)
	{
		$messenger = new Messenger();
		$messenger->setAction($action['action'], $action['route']);
		$actual = $messenger->redirect($datas['url'], $datas['time']);
		$this->assertEquals($expect, $actual);
	}
}
