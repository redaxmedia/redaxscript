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
	 * handle success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successData
	 */

	public function success($successData = array());

	/**
	 * handle error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorData
	 */

	public function error($errorData = array());
}
