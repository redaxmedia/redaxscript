<?php
namespace Redaxscript\Navigation;

use Redaxscript\Filesystem;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the language navigation
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Navigation
 * @author Henry Ruhs
 */

class Language extends NavigationAbstract
{
	/**
	 * options of the navigation
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-list-languages',
			'active' => 'rs-item-active'
		]
	];

	/**
	 * render the view
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('navigationLanguageStart');
		$outputItem = null;

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');

		/* languages directory */

		$languageFilesystem = new Filesystem\Filesystem();
		$languageFilesystem->init('languages');
		$languageFilesystemArray = $languageFilesystem->getSortArray();

		/* collect item output */

		foreach ($languageFilesystemArray as $value)
		{
			$value = substr($value, 0, 2);
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_registry->get('language') === $value ? $this->_optionArray['className']['active'] : null)
				->html($linkElement
					->copy()
					->attr(
					[
						'href' => $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . $this->_registry->get('languageRoute') . $value,
						'rel' => 'nofollow'
					])
					->text($this->_language->get($value, '_index'))
			);
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		$output .= Module\Hook::trigger('navigationLanguageEnd');
		return $output;
	}
}
