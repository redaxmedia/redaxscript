<?php
namespace Redaxscript\Tests;

use Redaxscript\Messenger;
use Redaxscript\Registry;

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
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
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
	 * @param array $actionArray
	 * @param string $expect
	 */

	public function testSuccess($success = null, $actionArray = array(), $expect = null)
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
	 * testWarning
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerWarning
	 *
	 * @param array $warning
	 * @param array $actionArray
	 * @param string $expect
	 */

	public function testWarning($warning = null, $actionArray = array(), $expect = null)
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
	 * @dataProvider providerError
	 *
	 * @param array $error
	 * @param array $actionArray
	 * @param string $expect
	 */

	public function testError($error = null, $actionArray = null, $expect = null)
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
	 * testInfo
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerInfo
	 *
	 * @param array $info
	 * @param array $actionArray
	 * @param string $expect
	 */

	public function testInfo($info = null, $actionArray = array(), $expect = null)
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
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRender
	 *
	 * @param array $render
	 * @param array $actionArray
	 * @param string $expect
	 */

	public function testRender($render = null, $actionArray = null, $expect = null)
	{
		/* setup */

		$messenger = new Messenger($this->_registry);
		$messenger->init();
		$messenger->setUrl($actionArray['text'], $actionArray['url'])->doRedirect($actionArray['timeout']);

		/* actual */

		$actual = $messenger->render($render['type'], $render['message'], $render['title']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
