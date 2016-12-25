<?php
namespace Redaxscript\Parser\Tag;

/**
 * interface to define a pseudo tag class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
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
