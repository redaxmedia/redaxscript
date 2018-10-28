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
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('login'));
		if ($settingModel->get('recovery'))
		{
			$linkElement = $element
				->copy()
				->init('a',
				[
					'href' => $parameterRoute . 'login/recover'
				]);
			$outputLegend = $linkElement->text($this->_language->get('recovery_question') . $this->_language->get('question_mark'));
		}
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-validate rs-form-default rs-form-login'
			],
			'button' =>
			[
				'submit' =>
				[
					'name' => self::class
				]
			]
		],
		[
			'captcha' => $settingModel->get('captcha')
		]);

		/* create the form */

		$formElement
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
		$output .= Module\Hook::trigger('loginFormEnd');
		return $output;
	}
}
