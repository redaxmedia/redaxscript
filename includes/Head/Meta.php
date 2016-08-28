<?php
namespace Redaxscript\Head;

use Redaxscript\Html;

/**
 * children class to create the meta tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @method append
 * @method prepend
 * @method clear
 */

class Meta extends HeadAbstract
{
	/**
	 * generate html element
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = '';

		/* html elements */

		$metaElement = new Html\Element();
		$metaElement->init('meta');

		foreach (self::$_collectionArray as $key => $value)
		{
			$output .= $metaElement
				->copy()
				->attr($value);
		}
		$this->clear();
		return $output;
	}
}