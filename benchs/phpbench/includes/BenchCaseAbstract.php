<?php
namespace Redaxscript\Benchs;

error_reporting(E_ERROR || E_PARSE);

/**
 * BenchCaseAbstract
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Benchs
 * @author Henry Ruhs
 */

abstract class BenchCaseAbstract
{
	/**
	 * getProvider
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 * @param boolean $assoc
	 *
	 * @return array
	 */

	public function getProvider($url = null, $assoc = true)
	{
		$contents = file_get_contents($url);
		return json_decode($contents, $assoc);
	}
}
