<?php
namespace Redaxscript\Content\Tag;

/**
 * interface to define a pseudo tag class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

interface TagInterface
{
	/**
	 * process the class
	 *
	 * @since 3.0.0
	 */

	public function process();
}
