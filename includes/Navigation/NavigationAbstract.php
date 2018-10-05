<?php
namespace Redaxscript\Navigation;

use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * abstract class to create a navigation class
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Navigation
 * @author Henry Ruhs
 */

abstract class NavigationAbstract implements NavigationInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * options of the navigation
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-list-navigation',
			'active' => 'rs-item-active'
		]
	];

	/**
	 * constructor of the class
	 *
	 * @since 3.3.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * init the class
	 *
	 * @since 3.3.0
	 *
	 * @param array $optionArray options of the navigation
	 */

	public function init(array $optionArray = [])
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
	}

	/**
	 * stringify the navigation
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	public function __toString() : string
	{
		return $this->render();
	}
}