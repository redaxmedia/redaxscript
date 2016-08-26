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
	 * render the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$titleArray
			= [
			'description' => $this->_registry->get('metaDescription') ? $this->_registry->get('metaDescription') : Db::getSetting('description'),
			'divider' => Db::getSetting('divider'),
			'title' => $this->_registry->get('metaTitle') ? $this->_registry->get('metaTitle') : Db::getSetting('title')
		];

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('title')
			->text($titleArray['title'] . $titleArray['divider'] . $titleArray['description']);
		return $titleElement;
	}
}