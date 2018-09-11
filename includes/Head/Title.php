<?php
namespace Redaxscript\Head;

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
	 * render the title
	 *
	 * @since 3.0.0
	 *
	 * @param string $text
	 *
	 * @return string|null
	 */

	public function render(string $text = null) : ?string
	{
		if ($text)
		{
			$titleElement = new Html\Element();
			return $titleElement
				->init('title')
				->text($text)
				->render();
		}
		return null;
	}
}
