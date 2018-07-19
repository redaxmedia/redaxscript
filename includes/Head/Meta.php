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

	public function render() : ?string
	{
		$output = null;

		/* html element */

		$metaElement = new Html\Element();
		$metaElement->init('meta');

		/* handle collection */

		$collectionArray = $this->_getCollectionArray();

		/* process collection */

		foreach ($collectionArray as $attribute)
		{
			if ($attribute['content'] && $attribute['name'] || !$attribute['name'])
			{
				$output .= $metaElement
					->copy()
					->attr($attribute);
			}
		}
		$this->clear();
		return $output;
	}
}