<?php
namespace Redaxscript\Head;

use Redaxscript\Html;

/**
 * children class to create the script tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 *
 * @method append
 * @method prepend
 * @method clear
 */

class Script extends HeadAbstract
{
	/**
	 * render the script
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = null;

		/* html elements */

		$scriptElement = new Html\Element();
		$scriptElement->init('script');
		$collectionKeys = array_keys(self::$_collectionArray);
		$lastKey = end($collectionKeys);

		/* process collection */

		foreach (self::$_collectionArray as $key => $value)
		{
			$output .= $scriptElement
				->copy()
				->attr($value);
			if ($key !== $lastKey)
			{
				$output .= PHP_EOL;
			}
		}
		$this->clear();
		return $output;
	}

	/**
	 * minify the script
	 *
	 * @since 3.0.0
	 */

	public function minify()
	{
		//this basicly sets a internal marker to influence the render method
	}

	/**
	 * inline the script
	 *
	 * @since 3.0.0
	 */

	public function inline()
	{
		//this basicly sets a internal marker to influence the render method
	}
}