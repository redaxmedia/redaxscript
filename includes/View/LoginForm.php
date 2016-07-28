<?php
namespace Redaxscript\View;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

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

	public function render()
	{
		$output = Hook::trigger('loginFormStart');
		$outputLegend = null;

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('login'));
		if (Db::getSetting('recovery'))
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
			'captcha' => Db::getSetting('captcha') > 0
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
				'required' => 'required'
			])
			->append('</li>');
		if (Db::getSetting('captcha') > 0)
		{
			$formElement
				->append('<li>')
				->captcha('task')
				->append('</li>');
		}
		$formElement->append('</ul></fieldset>');
		if (Db::getSetting('captcha') > 0)
		{
			$formElement->captcha('solution');
		}
		$formElement
			->token()
			->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('loginFormEnd');
		return $output;
	}
}
