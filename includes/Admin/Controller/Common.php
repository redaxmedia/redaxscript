<?php
namespace Redaxscript\Admin\Controller;

/**
 * children class to handle common tasks
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Common extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 4.0.0
	 *
	 * @param string $action
	 *
	 * @return string
	 */

	public function process(string $action = null) : string
	{
		return 'to be implemented: ' . __CLASS__ . ' ' . $action;
	}

	/**
	 * validate
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validate(array $postArray = []) : array
	{
	}

	/**
	 * create the common
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
	}

	/**
	 * update the common
	 *
	 * @since 4.0.0
	 *
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	protected function _update(array $updateArray = []) : bool
	{
	}
}
