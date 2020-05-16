<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the search form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class SearchForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @param string $table name of the table
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render(string $table = null) : string
	{
		$output = Module\Hook::trigger('searchFormStart');

		/* html element */

		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-search rs-form-search'
			],
			'button' =>
			[
				'submit' =>
				[
					'class' => 'rs-button-search',
					'name' => self::class
				]
			]
		]);

		/* create the form */

		$formElement
			->search(
			[
				'name' => 'search',
				'placeholder' => $this->_language->get('search'),
				'tabindex' => '1'
			])
			->hidden(
			[
				'name' => 'table',
				'value' => $table
			])
			->token()
			->submit($this->_language->get('search'));

		/* collect output */

		$output .= $formElement;
		$output .= Module\Hook::trigger('searchFormEnd');
		return $output;
	}
}
