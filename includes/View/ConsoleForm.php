<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the console form
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

	public function render() : string
	{
		$output = Module\Hook::trigger('consoleFormStart');
		$myUser = $this->_registry->get('myUser');

		/* html element */

		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-console-js-behavior rs-console-form-default'
			]
		]);
		$outputLabel = $myUser ? $myUser . '@' : null;
		$outputLabel .= $this->_registry->get('host') . ':~$';

		/* create the form */

		$formElement
			->label($outputLabel,
			[
				'class' => 'rs-console-js-label rs-console-label-default',
				'for' => 'prompt'
			])
			->text(
			[
				'autocapitalize' => 'off',
				'autofocus' => 'autofocus',
				'autocomplete' => 'off',
				'class' => 'rs-console-js-field rs-console-field-text',
				'id' => 'prompt',
				'name' => 'argv',
				'spellcheck' => 'false'
			]);

		/* collect output */

		$output .= $formElement;
		$output .= Module\Hook::trigger('consoleFormEnd');
		return $output;
	}
}
