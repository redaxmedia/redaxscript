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
 * @method append($attribute = null, $value = null)
 * @method prepend($attribute = null, $value = null)
 */

/* @todo: implement the method transportVar() that is using Assetic/Transport it is using the prependInline method */
/* @todo: transportVar($variableArray) can be used to load custom variables or override existing by merging the $variableArray with the stuff from Assetic/Transport */

/* @todo: implement the Assetic/Loader via a useCache() method - it should basically transform self::$_collectionArray[self::$_namespace] */
/* @todo: to 1. a single cached file and 2. keep the external (CDN) files untouched */

class Script extends HeadAbstract
{
	/**
	 * inline script
	 *
	 * @var string
	 */

	protected $_inline = null;

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
		$this->append('src', $source);
		return $this;
	}

	/**
	 * prepend script file
	 *
	 * @since 3.0.0
	 *
	 * @param string $source
	 *
	 * @return Script
	 */

	public function prependFile($source = null)
	{
		$this->prepend('src', $source);
		return $this;
	}

	/**
	 * remove script file
	 *
	 * @since 3.0.0
	 *
	 * @param string $source
	 *
	 * @return Link
	 */

	public function removeFile($source = null)
	{
		$this->remove('src', $source);
		return $this;
	}

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
		$this->_inline .= $inline;
		return $this;
	}

	/**
	 * prepend inline script
	 *
	 * @since 3.0.0
	 *
	 * @param string $inline
	 *
	 * @return Script
	 */

	public function prependInline($inline = null)
	{
		$this->_inline = $inline . $this->_inline;
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
		$collectionArray = self::$_collectionArray[self::$_namespace];
		$collectionKeys = array_keys($collectionArray);
		$lastKey = end($collectionKeys);

		/* process collection */

		foreach ($collectionArray as $key => $value)
		{
			$output .= $scriptElement
				->copy()
				->attr($value);
			if ($key !== $lastKey || $this->_inline)
			{
				$output .= PHP_EOL;
			}
		}

		/* collect inline */

		if ($this->_inline)
		{
			$output .= $scriptElement
				->copy()
				->text($this->_inline);
		}
		$this->clear();
		return $output;
	}

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
		$this->_inline = null;
		return $this;
	}
}