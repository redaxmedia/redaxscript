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

		/* process collection */

		foreach (self::$_collectionArray as $key => $value)
		{
			$output .= $scriptElement
				->copy()
				->attr($value);
		}
		$this->clear();
		return $output;
	}
}