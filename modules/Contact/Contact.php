<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Filter;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
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
		'version' => '3.0.0'
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
			echo self::process();
		}
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 *
	 * return Form
	 */

	public static function render()
	{
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'textarea' => array(
				'class' => 'rs-js-auto-resize rs-js-editor-textarea rs-field-textarea'
			),
			'button' => array(
				'submit' => array(
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
				'value' => Registry::get('myName')
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
				'value' => Registry::get('myEmail')
			))
			->append('</li><li>')
			->label(Language::get('url'), array(
				'for' => 'url'
			))
			->url(array(
				'id' => 'url',
				'name' => 'url'
			))
			->append('</li><li>')
			->label('* ' . Language::get('message'), array(
				'for' => 'text'
			))
			->textarea(array(
				'id' => 'text',
				'name' => 'text',
				'required' => 'required'
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

	public static function process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$urlFilter = new Filter\Url();
		$htmlFilter = new Filter\Html();
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
			return self::error($errorData);
		}

		/* handle success */

		else
		{
			return self::success(array(
				'author' => $postData['author'],
				'email' => $postData['email'],
				'url' => $postData['url'],
				'text' => $postData['text']
			));
		}
	}

	/**
	 * success
	 *
	 * @since 2.6.0
	 *
	 * @param array $successData
	 *
	 * @return string
	 */

	public static function success($successData = array())
	{
		$toArray = array(
			Db::getSettings('author') => Db::getSettings('email')
		);
		$fromArray = array(
			$successData['author'] => $successData['email']
		);
		$subject = Language::get('contact');
		$bodyArray = array(
			Language::get('author') . Language::get('colon') . ' ' . $successData['author'],
			'<br />',
			Language::get('email') . Language::get('colon') . ' <a href="mailto:' . $successData['email'] . '">' . $successData['email'] .'</a>',
			'<br />',
			Language::get('url') . Language::get('colon') . ' <a href="' . $successData['url'] . '">' . $successData['url'] .'</a>',
			'<br />',
			Language::get('message') . Language::get('colon') . ' ' . $successData['text']
		);

		/* send message */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();

		/* show success */

		$messenger = new Messenger();
		return $messenger->setAction(Language::get('home'), Registry::get('root'))->doRedirect()->success(Language::get('operation_completed'), Language::get('message_sent', '_contact'));
	}

	/**
	 * error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorData
	 *
	 * @return string
	 */

	public static function error($errorData = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction(Language::get('home'), Registry::get('root'))->error($errorData, Language::get('error_occurred'));
	}
}
