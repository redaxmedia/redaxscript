<?php
namespace Redaxscript\Head;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Registry;

/**
 * children class to process the title request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Title
{
	/**
	 * instance of the registry class
	 *
	 * @var object
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
	 * render the title
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$title = $this->_registry->get('metaTitle') ? $this->_registry->get('metaTitle') : Db::getSetting('title');
		$description = $this->_registry->get('metaDescription') ? $this->_registry->get('metaDescription') : Db::getSetting('description');
		$titleElement = new Html\Element();
		return $titleElement
			->init('title')
			->text($title . Db::getSetting('divider') . $description);
	}
}