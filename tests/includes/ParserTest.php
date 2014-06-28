<?php
include_once('tests/stubs.php');

/**
 * Redaxscript Parser Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Redaxscript_Parser_Test extends PHPUnit_Framework_TestCase
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

	protected function setUp()
	{
		$this->_registry = Redaxscript_Registry::getInstance();
	}

	/**
	 * providerParser
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerParser()
	{
		$contents = file_get_contents('tests/provider/parser.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testParseBreak
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $text
	 * @param string $route
	 * @param string $expect
	 *
	 * @dataProvider providerParser
	 */

	public function testParseBreak($registry = array(), $text = null, $route = null, $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$parser = new Redaxscript_Parser($this->_registry, $text, $route);

		/* result */

		$result = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
