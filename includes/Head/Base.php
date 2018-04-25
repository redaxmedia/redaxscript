<?php
namespace Redaxscript\Head;

use Redaxscript\Html;
use Redaxscript\Registry;

/**
 * children class to create the base tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Base implements HeadInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 */

	public function __construct(Registry $registry)
	{
		$this->_registry = $registry;
	}

	/**
	 * stringify the base
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString() : string
	{
		return $this->render();
	}

	/**
	 * render the base
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$baseElement = new Html\Element();
		$baseElement->init('base',
		[
			'href' => $this->_registry->get('root') . '/'
		]);
		return $baseElement->render();
	}
}