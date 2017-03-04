<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Filter;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
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
class Contact extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Contact',
		'alias' => 'Contact',
		'author' => 'Redaxmedia',
		'description' => 'Simple contact form',
		'version' => '3.0.0'
	];

	/**
	 * routerStart
	 *
	 * @since 3.0.0
	 */

	public function routerStart()
	{
		if ($this->_request->getPost(get_class()) === 'submit')
		{
			$this->_request->set('routerBreak', true);
			echo $this->process();
		}
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 *
	 * return Form
	 */

	public function render()
	{
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'textarea' =>
			[
				'class' => 'rs-js-auto-resize rs-js-editor-textarea rs-field-textarea'
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
			->legend()
			->append('<ul><li>')
			->label('* ' . $this->_language->get('author'),
			[
				'for' => 'author'
			])
			->text(
			[
				'id' => 'author',
				'name' => 'author',
				'readonly' => $this->_registry->get('myName') ? 'readonly' : null,
				'required' => 'required',
				'value' => $this->_registry->get('myName')
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('email'),
			[
				'for' => 'email'
			])
			->email(
			[
				'id' => 'email',
				'name' => 'email',
				'readonly' => $this->_registry->get('myEmail') ? 'readonly' : null,
				'required' => 'required',
				'value' => $this->_registry->get('myEmail')
			])
			->append('</li><li>')
			->label($this->_language->get('url'),
			[
				'for' => 'url'
			])
			->url(
			[
				'id' => 'url',
				'name' => 'url'
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('message'),
			[
				'for' => 'text'
			])
			->textarea(
			[
				'id' => 'text',
				'name' => 'text',
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

	public function process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$urlFilter = new Filter\Url();
		$htmlFilter = new Filter\Html();

		/* process post */

		$postArray =
		[
			'author' => $specialFilter->sanitize($this->_request->getPost('author')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'url' => $urlFilter->sanitize($this->_request->getPost('url')),
			'text' => nl2br($htmlFilter->sanitize($this->_request->getPost('text'))),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		];

		/* handle error */

		$messageArray = $this->_validate($postArray);
		if ($messageArray)
		{
			return $this->_error(
			[
				'message' => $messageArray
			]);
		}

		/* handle success */

		$mailArray =
		[
			'author' => $postArray['author'],
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'text' => $postArray['text']
		];

		/* mail */

		if ($this->_mail($mailArray))
		{
			return $this->_success();
		}
		return $this->_error(
		[
			'message' => $this->_language->get('something_wrong')
		]);
	}

	/**
	 * success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _success()
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setUrl($this->_language->get('home'), $this->_registry->get('root'))
			->doRedirect()
			->success($this->_language->get('operation_completed'), $this->_language->get('message_sent', '_contact'));
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

	protected function _error($errorArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setUrl($this->_language->get('home'), $this->_registry->get('root'))
			->error($errorArray['message'], $this->_language->get('error_occurred'));
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

	protected function _validate($postArray = [])
	{
		$emailValidator = new Validator\Email();
		$urlValidator = new Validator\Url();
		$captchaValidator = new Validator\Captcha();

		/* validate post */

		$messageArray = [];
		if (!$postArray['author'])
		{
			$messageArray[] = $this->_language->get('author_empty');
		}
		if (!$postArray['email'])
		{
			$messageArray[] = $this->_language->get('email_empty');
		}
		else if ($emailValidator->validate($postArray['email']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray['email'] = $this->_language->get('email_incorrect');
		}
		if ($postArray['url'] && $urlValidator->validate($postArray['url']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('url_incorrect');
		}
		if (!$postArray['text'])
		{
			$messageArray[] = $this->_language->get('message_empty');
		}
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('captcha_incorrect');
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

	protected function _mail($mailArray = [])
	{
		$toArray =
		[
			Db::getSetting('author') => Db::getSetting('email')
		];
		$fromArray =
		[
			$mailArray['author'] => $mailArray['email']
		];
		$subject = $this->_language->get('contact');
		$bodyArray =
		[
			$this->_language->get('author') . $this->_language->get('colon') . ' ' . $mailArray['author'],
			'<br />',
			$this->_language->get('email') . $this->_language->get('colon') . ' <a href="mailto:' . $mailArray['email'] . '">' . $mailArray['email'] . '</a>',
			'<br />',
			$this->_language->get('url') . $this->_language->get('colon') . ' <a href="' . $mailArray['url'] . '">' . $mailArray['url'] . '</a>',
			'<br />',
			$this->_language->get('message') . $this->_language->get('colon') . ' ' . $mailArray['text']
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}
