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
			'captcha' => Db::getSetting('captcha') > 0
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
			->submit()
			->reset();
		return $formElement;
	}

	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$urlFilter = new Filter\Url();
		$htmlFilter = new Filter\Html();

		/* process post */

		$postArray = array(
			'author' => $specialFilter->sanitize(Request::getPost('author')),
			'email' => $emailFilter->sanitize(Request::getPost('email')),
			'url' => $urlFilter->sanitize(Request::getPost('url')),
			'text' => nl2br($htmlFilter->sanitize(Request::getPost('text'))),
			'task' => Request::getPost('task'),
			'solution' => Request::getPost('solution')
		);

		/* handle error */

		$messageArray = self::_validate($postArray);
		if ($messageArray)
		{
			return self::_error(array(
				'message' => $messageArray
			));
		}

		/* handle success */

		$mailArray = array(
			'author' => $postArray['author'],
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'text' => $postArray['text']
		);

		/* mail */

		if (self::_mail($mailArray))
		{
			return self::_success();
		}
		return self::_error(array(
			'message' => Language::get('something_wrong')
		));
	}

	/**
	 * success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected static function _success()
	{
		$messenger = new Messenger(Registry::getInstance());
		return $messenger
			->setUrl(Language::get('home'), Registry::get('root'))
			->doRedirect()
			->success(Language::get('operation_completed'), Language::get('message_sent', '_contact'));
	}

	/**
	 * error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	protected static function _error($errorArray = array())
	{
		$messenger = new Messenger(Registry::getInstance());
		return $messenger
			->setUrl(Language::get('home'), Registry::get('root'))
			->error($errorArray['message'], Language::get('error_occurred'));
	}

	/**
	 * validate
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected static function _validate($postArray = array())
	{
		$emailValidator = new Validator\Email();
		$urlValidator = new Validator\Url();
		$captchaValidator = new Validator\Captcha();

		/* validate post */

		$messageArray = array();
		if (!$postArray['author'])
		{
			$messageArray[] = Language::get('author_empty');
		}
		if (!$postArray['email'])
		{
			$messageArray[] = Language::get('email_empty');
		}
		else if ($emailValidator->validate($postArray['email']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray['email'] = Language::get('email_incorrect');
		}
		if ($postArray['url'] && $urlValidator->validate($postArray['url']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = Language::get('url_incorrect');
		}
		if (!$postArray['text'])
		{
			$messageArray[] = Language::get('message_empty');
		}
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = Language::get('captcha_incorrect');
		}
		return $messageArray;
	}

	/**
	 * mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray
	 *
	 * @return boolean
	 */

	protected static function _mail($mailArray = array())
	{
		$toArray = array(
			Db::getSetting('author') => Db::getSetting('email')
		);
		$fromArray = array(
			$mailArray['author'] => $mailArray['email']
		);
		$subject = Language::get('contact');
		$bodyArray = array(
			Language::get('author') . Language::get('colon') . ' ' . $mailArray['author'],
			'<br />',
			Language::get('email') . Language::get('colon') . ' <a href="mailto:' . $mailArray['email'] . '">' . $mailArray['email'] . '</a>',
			'<br />',
			Language::get('url') . Language::get('colon') . ' <a href="' . $mailArray['url'] . '">' . $mailArray['url'] . '</a>',
			'<br />',
			Language::get('message') . Language::get('colon') . ' ' . $mailArray['text']
		);

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}
