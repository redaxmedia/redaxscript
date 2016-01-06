<?php
namespace Redaxscript\Controller;

/**
 * abstract class to create a controller class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

abstract class ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 3.0.0
	 */

	protected function _process()
	{
	}

	/**
	 * handle success
	 *
	 * @since 3.0.0
	 *
	 * @param array $postData
	 */

	protected function _success($postData = array())
	{
	}

	/**
	 * handle error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorData
	 */

	protected function _error($errorData = array())
	{
	}
}
