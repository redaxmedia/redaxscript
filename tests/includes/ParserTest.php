<?php
use Redaxscript\Language;
use Redaxscript\Parser;
use Redaxscript\Registry;

/**
 * ParserTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ParserTest extends PHPUnit_Framework_TestCase
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
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
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
		$options = array(
			'className' => array(
				'break' => 'link-read-more',
				'code' => 'box-code'
			)
		);
		$parser = new Parser($this->_registry, $this->_language, $text, $route, $options);

		/* result */

		$result = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
