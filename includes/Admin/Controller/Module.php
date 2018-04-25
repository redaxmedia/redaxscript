<?php
namespace Redaxscript\Admin\Controller;

/**
 * children class to process the admin module request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Module extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function process() : string
	{
		return 'to be implemented: ' . __CLASS__;
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
	 * create the module
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
	 * update the module
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
