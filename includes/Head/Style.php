<?php
namespace Redaxscript\Head;

use Redaxscript\Singleton;

/**
 * children class to create the style tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 */

class extends Singleton implements HeadInterface
{
	//@todo: this class basicly collects inline css with appendInline(), prependInline(), render() and clear()
	//@todo: it does not use the HeadAbstract because there is no append() and prepend() allowed

	/**
	 * collection of the style
	 *
	 * @var array
	 */

	protected static $_collectionArray = [];

	/**
	 * stringify the collection
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		return $this->render();
	}

	/**
	 * render the style
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
	}
}