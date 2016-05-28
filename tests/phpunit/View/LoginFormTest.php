<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * LoginFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class LoginFormTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		Db::setSetting('captcha', 1);
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::setSetting('captcha', 0);
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
		return $this->getProvider('tests/provider/View/login_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($expectArray = array())
	{
		/* setup */

		$loginForm = new View\LoginForm($this->_registry, $this->_language);

		/* actual */

		$actual = $loginForm->render();

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
