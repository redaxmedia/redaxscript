<?php
namespace Redaxscript\Admin\Controller;

/**
 * children class to process the admin setting request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Setting extends ControllerAbstract
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
	 * update the setting
	 *
	 * @since 4.0.0
	 *
	 * @param array $updateArray array of the create
	 *
	 * @return bool
	 */

	protected function _update(array $updateArray = []) : bool
	{
	}
}
