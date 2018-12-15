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

class Template extends NavigationAbstract
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
			'list' => 'rs-list-templates',
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
		$output = Module\Hook::trigger('navigationTemplateStart');
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

		/* template directory */

		$templateFilesystem = new Filesystem\Filesystem();
		$templateFilesystem->init('templates', false,
		[
			'admin',
			'console',
			'install'
		]);
		$templateFilesystemArray = $templateFilesystem->getSortArray();

		/* collect item output */

		foreach ($templateFilesystemArray as $value)
		{
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_registry->get('template') === $value ? $this->_optionArray['className']['active'] : null)
				->html($linkElement
					->copy()
					->attr(
					[
						'href' => $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . $this->_registry->get('templateRoute') . $value,
						'rel' => 'nofollow'
					])
					->text($value)
				);
		}

		/* collect output */

		$output .= $listElement->html($outputItem ? : $itemElement->text($this->_language->get('template_no')));
		$output .= Module\Hook::trigger('navigationTemplateEnd');
		return $output;
	}
}
