<?php
namespace Redaxscript\Tests;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

error_reporting(E_ERROR || E_PARSE);

/**
 * TestCase
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

abstract class TestCaseAbstract extends PHPUnit_Framework_TestCase
{
	/**
	 * getProvider
	 *
	 * @since 2.2.0
	 *
	 * @param string $json
	 * @param boolean $assoc
	 *
	 * @return array
	 */

	public function getProvider($json = null, $assoc = true)
	{
		$contents = file_get_contents($json);
		$output = json_decode($contents, $assoc);
		return $output;
	}

	/**
	 * callMethod
	 *
	 * @since 3.0.0
	 *
	 * @param object $object
	 * @param string $method
	 * @param array $argumentArray
	 *
	 * @return mixed
	 */

	public function callMethod($object = null, $method = null, $argumentArray = array())
	{
		$reflectionObject = new ReflectionClass($object);
		$reflectionMethod = $reflectionObject->getMethod($method);
		$reflectionMethod->setAccessible(true);
		return $reflectionMethod->invokeArgs($object, $argumentArray);
	}

	/**
	 * assertString
	 *
	 * @since 3.0.0
	 *
	 * @param string $actual
	 */

	public function assertString($actual = null)
	{
		$this->assertTrue(is_string($actual));
	}
}
