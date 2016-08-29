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
 */

class Script extends HeadAbstract
{
	//@todo 1. protected static $_inline = null; or protected $_inline = null; depends if static is needed!?

	/**
	 * append script file
	 *
	 * @since 3.0.0
	 *
	 * @param string $source
	 *
	 * @return Script
	 */

	public function appendFile($source = null)
	{
		//@todo: 2. this will do $this->append('src', $source); - finally it is just an optional shortcut
		return $this;
	}

	//@todo 2.1 we need prependFile() too

	/**
	 * append inline script
	 *
	 * @since 3.0.0
	 *
	 * @param string $inline
	 *
	 * @return Script
	 */

	public function appendInline($inline = null)
	{
		//@todo: 3. this will do $this->_inline .= $inline; (maybe self::$_inline)
		return $this;
	}

	//@todo 3.1 we need prependFile() too

	/**
	 * clear the script
	 *
	 * @since 3.0.0
	 *
	 * @return Script
	 */

	public function clear()
	{
		parent::clear();
		//@todo: 4. we use parent::clear() and also need to clear the $inline property
		return $this;
	}

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
		//@todo: 5. if there is something in $this->_inline, we have to render another script tag with that inline code
		$this->clear();
		return $output;
	}
}