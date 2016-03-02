<?php
namespace Redaxscript\Tests;

use Redaxscript\Controller\RegisterPost;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * RegisterPostTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */
class RegisterPostTest extends TestCase
{
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

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

	protected function setUp()
	{
		$this->_request = Request::getInstance();
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * providerGetArray
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetArray()
	{
		return $this->getProvider('tests/provider/Controller/register_post_array.json');
	}

	/**
	 * test registerPost
	 *
	 * @since 3.0.0
	 *
	 * @param array $request
	 * @param array $registry
	 * @param array $expect
	 *
	 * @dataProvider providerGetArray
	 */

	public function testProcess($request = array(), $registry = array(), $expect = null)
	{
		/* setup */

		$this->_request->init($request);
		$this->_registry->init($registry);

		/* actual */
		$register = new RegisterPost($this->_request, $this->_registry, $this->_language);
		$actual = $register->_process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

}
