<?php
namespace Redaxscript\Head;

use Redaxscript\Html;

/**
 * children class to create the link tag
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

class Link extends HeadAbstract
{
	/**
	 * render the link
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = null;

		/* html elements */

		$metaElement = new Html\Element();
		$metaElement->init('link');
		$collectionKeys = array_keys(self::$_collectionArray);
		$lastKey = end($collectionKeys);

		/* process collection */

		foreach (self::$_collectionArray as $key => $value)
		{
			$output .= $metaElement
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
}