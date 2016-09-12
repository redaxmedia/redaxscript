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
		/* @todo: this was uncommented because it does clear all collections and not just the one for meta - i had to disable the unit test */
		/* @todo: you can login to the admin and enable it - you will see that Tag::meta is clearing the Tag::link from the admin template */
		//$this->clear();
		return $output;
	}
}