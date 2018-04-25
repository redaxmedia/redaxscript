<?php
namespace Redaxscript\Head;

use Redaxscript\Html;

/**
 * children class to create the meta tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Meta extends HeadAbstract
{
	/**
	 * render the meta
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public function render()
	{
		$output = null;

		/* html elements */

		$metaElement = new Html\Element();
		$metaElement->init('meta');

		/* handle collection */

		$collectionArray = $this->_getCollectionArray();
		$collectionKeys = array_keys($collectionArray);
		$lastKey = end($collectionKeys);

		/* process collection */

		foreach ($collectionArray as $key => $attribute)
		{
			if ($attribute['content'] && $attribute['name'] || !$attribute['name'])
			{
				$output .= $metaElement
					->copy()
					->attr($attribute);
				if ($key !== $lastKey)
				{
					$output .= PHP_EOL;
				}
			}
		}
		$this->clear();
		return $output;
	}
}