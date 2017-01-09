<?php
namespace Redaxscript\Head;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Registry;

/**
 * children class to create the title tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Title implements HeadInterface
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
	 * stringify the title
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		$render = $this->render();
		if ($render)
		{
			return $render;
		}
		return '<!-- Redaxscript\Head\Title -->';
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
		$titleArray =
		[
			'title' => $this->_registry->get('metaTitle') ? $this->_registry->get('metaTitle') : Db::getSetting('title'),
			'description' => $this->_registry->get('metaDescription') ? $this->_registry->get('metaDescription') : Db::getSetting('description')
		];
		$divider = $this->_registry->get('metaDivider') ? $this->_registry->get('metaDivider') : Db::getSetting('divider');

		/* html elements */

		$titleElement = new Html\Element();
		$titleText = implode($divider, array_filter($titleArray));
		if ($titleText)
		{
			return $titleElement
				->init('title')
				->text($titleText)
				->render();
		}
	}
}