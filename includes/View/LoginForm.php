<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;

/**
 * children class to create the login form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class LoginForm extends ViewAbstract
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
		$output = Module\Hook::trigger('loginFormStart');
		$outputLegend = null;
		$settingModel = new Model\Setting();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('login'));
		if ($settingModel->get('recovery'))
		{
			$linkElement = new Html\Element();
			$linkElement->init('a',
			[
				'href' => $this->_registry->get('parameterRoute') . 'login/recover'
			]);
			$outputLegend = $linkElement->text($this->_language->get('recovery_question') . $this->_language->get('question_mark'));
		}
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-validate-form rs-form-default rs-form-login'
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
			->append('<fieldset>')
			->legend($outputLegend)
			->append('<ul><li>')
			->label('* ' . $this->_language->get('user'),
			[
				'for' => 'user'
			])
			->text(
			[
				'autofocus' => 'autofocus',
				'id' => 'user',
				'name' => 'user',
				'pattern' => '[a-zA-Z0-9]{1,30}',
				'required' => 'required'
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('password'),
			[
				'for' => 'password'
			])
			->password(
			[
				'id' => 'password',
				'name' => 'password',
				'pattern' => '[a-zA-Z0-9]{1,30}',
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
		$formElement->append('</ul></fieldset>');
		if ($settingModel->get('captcha') > 0)
		{
			$formElement->captcha('solution');
		}
		$formElement
			->token()
			->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('loginFormEnd');
		return $output;
	}
}
