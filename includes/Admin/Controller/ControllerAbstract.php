<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Controller\ControllerAbstract as BaseControllerAbstract;

/**
 * abstract class to create a admin controller class
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

abstract class ControllerAbstract extends BaseControllerAbstract
{
	/**
	 * messenger factory
	 *
	 * @since 4.0.0
	 *
	 * @return Admin\Messenger
	 */

	protected function _messengerFactory()
	{
		return new Admin\Messenger($this->_registry);
	}
}
