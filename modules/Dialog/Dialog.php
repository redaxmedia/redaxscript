<?php
namespace Redaxscript\Modules\Dialog;

use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * shared module to replace alert, confirm and prompt
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Dialog extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Dialog',
		'alias' => 'Dialog',
		'author' => 'Redaxmedia',
		'description' => 'Shared module to replace alert, confirm and prompt',
		'version' => '4.5.0',
		'license' => 'MIT'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'overlay' => 'rs-overlay-dialog',
			'component' => 'rs-component-dialog',
			'title' => 'rs-title-dialog',
			'box' => 'rs-box-dialog',
			'text' => 'rs-text-dialog',
			'field' => 'rs-js-input rs-field-default rs-field-text',
			'button' => 'rs-button-default',
			'buttonOk' => 'rs-js-ok',
			'buttonCancel' => 'rs-js-cancel'
		]
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart() : void
	{
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$thirdParameter = $this->_registry->get('thirdParameter');

		/* link */

		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('modules/Dialog/dist/styles/dialog.min.css');

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				'modules/Dialog/assets/scripts/init.js',
				'modules/Dialog/dist/scripts/dialog.min.js'
			]);

		/* router */

		if ($firstParameter === 'module' && ($secondParameter === 'dialog' || $secondParameter === 'admin-dialog'))
		{
			$this->_registry->set('renderBreak', true);
			$dialog = $secondParameter === 'admin-dialog' ? new Admin\Dialog($this->_registry, $this->_request, $this->_language, $this->_config) : $this;
			$message = $this->_request->getStream('message');
			$title = $this->_request->getStream('title');

			/* run as needed */

			if ($thirdParameter === 'alert')
			{
				echo $dialog->alert($message, $title);
			}
			if ($thirdParameter === 'confirm')
			{
				echo $dialog->confirm($message, $title);
			}
			if ($thirdParameter === 'prompt')
			{
				echo $dialog->prompt($message, $title);
			}
		}
	}

	/**
	 * alert
	 *
	 * @since 4.0.0
	 *
	 * @param string $message message of the alert
	 * @param string $title title of the alert
	 *
	 * @return string
	 */

	public function alert(string $message = null, string $title = null) : string
	{
		return $this->_dialog('alert', $message, $title ? : $this->_language->get('_dialog')['alert']);
	}

	/**
	 * confirm
	 *
	 * @since 4.0.0
	 *
	 * @param string $message message of the confirm
	 * @param string $title title of the confirm
	 *
	 * @return string
	 */

	public function confirm(string $message = null, string $title = null) : string
	{
		return $this->_dialog('confirm', $message, $title ? : $this->_language->get('_dialog')['confirm']);
	}

	/**
	 * prompt
	 *
	 * @since 4.0.0
	 *
	 * @param string $message message of the prompt
	 * @param string $title title of the prompt
	 *
	 * @return string
	 */

	public function prompt(string $message = null, string $title = null) : string
	{
		return $this->_dialog('prompt', $message, $title ? : $this->_language->get('_dialog')['prompt']);
	}

	/**
	 * dialog
	 *
	 * @since 4.0.0
	 *
	 * @param string $type type of the dialog
	 * @param string $message message of the dialog
	 * @param string $title title of the dialog
	 *
	 * @return string
	 */

	protected function _dialog(string $type = null, string $message = null, string $title = null) : string
	{
		$output = null;

		/* html elements */

		$element = new Html\Element();
		$overlayElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['overlay']
			]);
		$dialogElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['component']
			]);
		$titleElement = $element
			->copy()
			->init('h3',
			[
				'class' => $this->_optionArray['className']['title']
			])
			->text($title);
		$boxElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['box']
			]);
		$textElement = $message ? $element
			->copy()
			->init('p',
			[
				'class' => $this->_optionArray['className']['text']
			])
			->text($message) : null;
		$fieldElement = $type === 'prompt' ? $element
			->copy()
			->init('input',
			[
				'class' => $this->_optionArray['className']['field']
			]) : null;
		$buttonElement = $element
			->copy()
			->init('button',
			[
				'class' => $this->_optionArray['className']['button']
			]);
		$buttonOkElement = $buttonElement
			->copy()
			->addClass($this->_optionArray['className']['buttonOk'])
			->text($this->_language->get('ok'));
		$buttonCancelElement = $type === 'confirm' || $type === 'prompt' ? $buttonElement
			->copy()
			->addClass($this->_optionArray['className']['buttonCancel'])
			->text($this->_language->get('cancel')) : null;

		/* collect output */

		$output = $overlayElement->html(
			$dialogElement->html(
				$titleElement .
				$boxElement->html(
					$textElement .
					$fieldElement .
					$buttonOkElement .
					$buttonCancelElement
				)
			)
		);
		return $output;
	}
}
