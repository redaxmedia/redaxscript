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
 * @method append($attribute = null, $value = null)
 * @method prepend($attribute = null, $value = null)
 * @method clear
 */

class Link extends HeadAbstract
{
	/**
	 * append file
	 *
	 * @since 3.0.0
	 *
	 * @param string $reference
	 *
	 * @return Link
	 */

	public function appendFile($reference = null)
	{
		$this->append('href', $reference);
		return $this;
	}

	/**
	 * prepend file
	 *
	 * @since 3.0.0
	 *
	 * @param string $reference
	 *
	 * @return Link
	 */

	public function prependFile($reference = null)
	{
		$this->prepend('href', $reference);
		return $this;
	}

	/**
	 * render the link
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