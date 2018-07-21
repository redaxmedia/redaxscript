<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;

/**
 * children class to create the recover form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class RecoverForm extends ViewAbstract
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
		$output = Module\Hook::trigger('recoverFormStart');
		$settingModel = new Model\Setting();

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('recovery'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-validate rs-form-default rs-form-recover'
			],
			'button' =>
			[
				'submit' =>
				[
					'name' => get_class()
				]
			]
		],
		[
			'captcha' => $settingModel->get('captcha') > 0
		]);

		/* create the form */

		$formElement
			->legend($this->_language->get('recovery_request') . $this->_language->get('point'))
			->append('<ul><li>')
			->label('* ' . $this->_language->get('email'),
			[
				'for' => 'email'
			])
			->email(
			[
				'autofocus' => 'autofocus',
				'id' => 'email',
				'name' => 'email',
				'required' => 'required'
			])
			->append('</li>');
		if ($settingModel->get('captcha') > 0)
		{
			$formElement
				->append('<li>')
				->captcha('task')
				->append('</li>');
		}
		$formElement->append('</ul>');
		if ($settingModel->get('captcha') > 0)
		{
			$formElement->captcha('solution');
		}
		$formElement
			->token()
			->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('recoverFormEnd');
		return $output;
	}
}
