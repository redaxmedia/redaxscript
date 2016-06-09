<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to generate the console form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ConsoleForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = Hook::trigger('consoleFormStart');

		/* html elements */

		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-console-js-form rs-console-form-default'
			)
		));
		$myUser = $this->_registry->get('myUser');
		$outputLabel = $myUser ? $myUser . '@' : null;
		$outputLabel .= $this->_registry->get('host') . ':~$';

		/* create the form */

		$formElement
			->label($outputLabel, array(
				'class' => 'rs-console-js-label rs-console-label-default',
				'for' => 'prompt'
			))
			->text(array(
				'autocapitalize' => 'off',
				'autofocus' => 'autofocus',
				'autocomplete' => 'off',
				'class' => 'rs-console-js-field rs-console-field-text',
				'id' => 'prompt',
				'name' => 'argv',
				'spellcheck' => 'false'
			));

		/* collect output */

		$output .= $formElement;
		$output .= Hook::trigger('consoleFormEnd');
		return $output;
	}
}