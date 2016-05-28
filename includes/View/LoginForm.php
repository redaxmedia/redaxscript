<?php
namespace Redaxscript\View;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to generate the login form
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

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2', array(
				'class' => 'rs-title-content'
			))
			->text($this->_language->get('login'));
		if (Db::getSetting('recovery'))
		{
			$linkElement = new Html\Element();
			$linkElement->init('a', array(
				'href' => $this->_registry->get('parameterRoute') . 'login/recover',
				'rel' => 'no-follow'
			));
			$legendHTML = $linkElement->text($this->_language->get('recovery_question') . $this->_language->get('question_mark'));
		}
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-validate-form rs-form-default rs-form-login'
			),
			'button' => array(
				'submit' => array(
					'name' => get_class()
				)
			)
		), array(
			'captcha' => Db::getSetting('captcha') > 0
		));

		/* create the form */

		$formElement
			->append('<fieldset>')
			->legend($legendHTML)
			->append('<ul><li>')
			->label('* ' . $this->_language->get('user'), array(
				'for' => 'user'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'id' => 'user',
				'name' => 'user',
				'required' => 'required'
			))
			->append('</li><li>')
			->label('* ' . $this->_language->get('password'), array(
				'for' => 'password'
			))
			->password(array(
				'id' => 'password',
				'name' => 'password',
				'required' => 'required'
			))
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
