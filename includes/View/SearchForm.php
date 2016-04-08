<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate the search form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class SearchForm implements ViewInterface
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

	public function render($table = 'articles')
	{
		$output = Hook::trigger('searchFormStart');

		/* html elements */

		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-validate-search rs-form-search',
				'action' => ''
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
				'placeholder' => Language::get('search'),
				'tabindex' => '1'
			))
			->hidden(array(
				'name' => 'table',
				'value' => $table
			))
			->token()
			->submit(Language::get('search'));

		/* collect output */

		$output .= $formElement;
		$output .= Hook::trigger('searchFormEnd');
		return $output;
	}
}
