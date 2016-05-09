<?php
namespace Redaxscript\Tests;

use PHPUnit_Framework_TestCase;

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

class TestCase extends PHPUnit_Framework_TestCase
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
}
