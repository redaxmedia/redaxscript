<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;

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

	public function render($table = null)
	{
		$output = Hook::trigger('searchFormStart');

		/* html elements */

		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-validate-search rs-form-search'
			),
			'button' => array(
				'submit' => array(
					'class' => 'rs-button-search',
					'name' => get_class()
				)
			)
		));

		/* create the form */

		$formElement
			->search(array(
				'name' => 'search',
				'placeholder' => $this->_language->get('search'),
				'tabindex' => '1'
			))
			->hidden(array(
				'name' => 'table',
				'value' => $table
			))
			->token()
			->submit($this->_language->get('search'));

		/* collect output */

		$output .= $formElement;
		$output .= Hook::trigger('searchFormEnd');
		return $output;
	}
}
