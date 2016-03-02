<?php
namespace Redaxscript\Controller;

/**
 * interface to define a view
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

	public function _process();

	/**
	 * handle success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successData
	 */

	public function _success($successData = array());

	/**
	 * handle error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorData
	 */

	public function _error($errorData = array());
}
