<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Filter;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * simple contact form
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Contact extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Contact',
		'alias' => 'Contact',
		'author' => 'Redaxmedia',
		'description' => 'Simple contact form',
		'version' => '2.6.0'
	);

	/**
	 * routerStart
	 *
	 * @since 3.0.0
	 */

	public static function routerStart()
	{
		if (Request::getPost(get_class()) === 'submit')
		{
			Registry::set('routerBreak', true);
			self::_process();
		}
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 */

	public static function render()
	{
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-validate-form rs-form-default'
			),
			'label' => array(
				'class' => 'rs-label'
			),
			'textarea' => array(
				'class' => 'rs-js-auto-resize js-editor-textarea field-textarea'
			),
			'input' => array(
				'email' => array(
					'class' => 'rs-field-text'
				),
				'number' => array(
					'class' => 'rs-field-text'
				),
				'text' => array(
					'class' => 'rs-field-text'
				),
				'url' => array(
					'class' => 'rs-field-text'
				)
			),
			'button' => array(
				'submit' => array(
					'class' => 'rs-js-submit rs-button-default',
					'name' => get_class()
				),
				'reset' => array(
					'class' => 'rs-js-reset rs-button-default',
					'name' => get_class()
				)
			)
		), array(
			'captcha' => Db::getSettings('captcha') > 0
		));

		/* create the form */

		$formElement
			->append('<fieldset>')
			->legend()
			->append('<ul><li>')
			->label('* ' . Language::get('author'), array(
				'for' => 'author'
			))
			->text(array(
				'id' => 'author',
				'name' => 'author',
				'readonly' => Registry::get('myName') ? 'readonly' : null,
				'required' => 'required',
				'value' => Registry::get('myName') ? Registry::get('myName') : Request::getPost('author')
			))
			->append('</li><li>')
			->label('* ' . Language::get('email'), array(
				'for' => 'email'
			))
			->email(array(
				'id' => 'email',
				'name' => 'email',
				'readonly' => Registry::get('myEmail') ? 'readonly' : null,
				'required' => 'required',
				'value' => Registry::get('myEmail') ? Registry::get('myEmail') : Request::getPost('email')
			))
			->append('</li><li>')
			->label(Language::get('url'), array(
				'for' => 'url'
			))
			->url(array(
				'id' => 'url',
				'name' => 'url',
				'value' => Request::getPost('url')
			))
			->append('</li><li>')
			->label('* ' . Language::get('message'), array(
				'for' => 'text'
			))
			->textarea(array(
				'id' => 'text',
				'name' => 'text',
				'required' => 'required',
				'value' => Request::getPost('text')
			))
			->append('</li>');
		if (Db::getSettings('captcha') > 0)
		{
			$formElement
				->append('<li>')
				->captcha('task')
				->append('</li>');
		}
		$formElement->append('</ul></fieldset>');
		if (Db::getSettings('captcha') > 0)
		{
			$formElement->captcha('solution');
		}
		$formElement
			->token()
			->submit()
			->reset();
		return $formElement;
	}

	/**
	 * process
	 *
	 * @since 2.6.0
	 */

	public static function _process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$urlFilter  = new Filter\Url();
		$htmlFilter  = new Filter\Html();
		$emailValidator = new Validator\Email();
		$urlValidator = new Validator\Url();
		$captchaValidator = new Validator\Captcha();

		/* process post */

		$postData = array(
			'author' => $specialFilter->sanitize(Request::getPost('author')),
			'email' => $emailFilter->sanitize(Request::getPost('email')),
			'url' => $urlFilter->sanitize(Request::getPost('url')),
			'text' => nl2br($htmlFilter->sanitize(Request::getPost('text'))),
			'task' => Request::getPost('task'),
			'solution' => Request::getPost('solution')
		);

		/* validate post */

		if (!$postData['author'])
		{
			$errorData['author'] = Language::get('author_empty');
		}
		if (!$postData['email'])
		{
			$errorData['email'] = Language::get('email_empty');
		}
		else if ($emailValidator->validate($postData['email']) === Validator\ValidatorInterface::FAILED)
		{
			$errorData['email'] = Language::get('email_incorrect');
		}
		if ($errorData['url'] && $urlValidator->validate($postData['url']) === Validator\ValidatorInterface::FAILED)
		{
			$errorData['url'] = Language::get('url_incorrect');
		}
		if (!$postData['text'])
		{
			$errorData['text'] = Language::get('message_empty');
		}
		if ($captchaValidator->validate($postData['task'], $postData['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$errorData['captcha'] = Language::get('captcha_incorrect');
		}

		/* handle error */

		if ($errorData)
		{
			notification(Language::get('error_occurred'), $errorData, Language::get('home'), Registry::get('root'));
		}

		/* handle success */

		else
		{
			notification(Language::get('operation_completed'), Language::get('message_sent', '_contact'), Language::get('home'), Registry::get('root'));
			self::_send($postData);
		}
	}

	/**
	 * send
	 *
	 * @since 2.6.0
	 *
	 * @param array $postData
	 */

	public static function _send($postData = array())
	{
		$toArray = array(
			Db::getSettings('author') => Db::getSettings('email')
		);
		$fromArray = array(
			$postData['author'] => $postData['email']
		);
		$subject = Language::get('contact');
		$bodyArray = array(
			Language::get('author') . Language::get('colon') . ' ' . $postData['author'],
			'<br />',
			Language::get('email') . Language::get('colon') . ' <a href="mailto:' . $postData['email'] . '">' . $postData['email'] .'</a>',
			'<br />',
			Language::get('url') . Language::get('colon') . ' <a href="' . $postData['url'] . '">' . $postData['url'] .'</a>',
			'<br />',
			Language::get('message') . Language::get('colon') . ' ' . $postData['text']
		);

		/* send message */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();
	}
}