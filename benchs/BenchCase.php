<?php
namespace Redaxscript\Benchs;

/**
 * BenchCase
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Benchs
 * @author Henry Ruhs
 */

class BenchCase
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
		$output = json_decode($contents, $assoc);
		return $output;
	}
}
