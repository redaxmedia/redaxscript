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
	 * @param string $json
	 * @param boolean $assoc
	 *
	 * @return array
	 */

	public function getProvider($json = null, $assoc = true)
	{
		$contents = file_get_contents($json);
		return json_decode($contents, $assoc);
	}
}
