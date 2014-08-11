<?php
namespace Redaxscript\Tests;
use PHPUnit_Framework_TestCase;

/**
 * Helper
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Helper extends PHPUnit_Framework_TestCase
{
	/**
	 * getProvider
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	function getProvider($json = null)
	{
		$contents = file_get_contents($json);
		$output = json_decode($contents, true);
		return $output;
	}
}
