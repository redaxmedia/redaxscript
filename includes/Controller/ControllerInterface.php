<?php
namespace Redaxscript\Controller;

/**
 * interface to define a controller
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

interface ControllerInterface
{
	/**
	 * process the class
	 *
	 * @since 3.0.0
	 */

	public function process();

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 */

	public function success();

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 */

	public function error();
}
