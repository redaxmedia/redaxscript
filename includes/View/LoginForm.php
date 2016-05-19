<?php
namespace Redaxscript\View;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate the login form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class LoginForm implements ViewInterface
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
			->text(Language::get('login'));
		if (Db::getSetting('recovery'))
		{
			$linkElement = new Html\Element();
			$linkElement->init('a', array(
				'href' => Registry::get('parameterRoute') . 'login/recover',
				'rel' => 'no-follow'
			));
			$legendHTML = $linkElement->text(Language::get('recovery_question') . Language::get('question_mark'));
		}
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
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
			->label('* ' . Language::get('user'), array(
				'for' => 'user'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'id' => 'user',
				'name' => 'user',
				'required' => 'required'
			))
			->append('</li><li>')
			->label('* ' . Language::get('password'), array(
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
