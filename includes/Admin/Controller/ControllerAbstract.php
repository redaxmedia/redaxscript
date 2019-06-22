<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Controller\ControllerAbstract as BaseControllerAbstract;
use Redaxscript\View;

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
	 * @return View\Helper\Messenger
	 */

	protected function _messengerFactory() : View\Helper\Messenger
	{
		return new Admin\View\Helper\Messenger($this->_registry);
	}
}
