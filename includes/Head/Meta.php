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
	 * render the meta
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
		$metaElement->init('meta');
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