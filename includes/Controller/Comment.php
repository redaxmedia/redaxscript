<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the comment request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Comment extends ControllerAbstract
{
	/**
	 * process the class
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
			'text' => $htmlFilter->sanitize($this->_request->getPost('text')),
			'article' => $specialFilter->sanitize($this->_request->getPost('article')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		];
		$route = build_route('articles', $postArray['article']);

		/* handle error */

		$messageArray = $this->_validate($postArray);
		if ($messageArray)
		{
			return $this->_error(
			[
				'route' => $route,
				'message' => $messageArray
			]);
		}

		/* handle success */

		$createArray =
		[
			'author' => $postArray['author'],
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'text' => $postArray['text'],
			'language' => Db::forTablePrefix('articles')->whereIdIs($postArray['article'])->findOne()->language,
			'article' => $postArray['article'],
			'status' => Db::getSetting('verification') ? 0 : 1
		];
		$mailArray =
		[
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'route' => $route,
			'author' => $postArray['author'],
			'text' => $postArray['text'],
			'article' => Db::forTablePrefix('articles')->whereIdIs($postArray['article'])->findOne()->title
		];

		/* create */

		if (!$this->_create($createArray))
		{
			return $this->_error(
			[
				'route' => $route,
				'message' => $this->_language->get('something_wrong')
			]);
		}

		/* mail */

		if (!$this->_mail($mailArray))
		{
			return $this->_warning(
			[
				'route' => $route,
				'message' => $this->_language->get('email_failed')
			]);
		}
		return $this->_success(
		[
			'route' => $route,
			'timeout' => Db::getSetting('notification') ? 2 : 0,
			'message' => Db::getSetting('moderation') ? $this->_language->get('comment_moderation') : $this->_language->get('comment_sent')
		]);
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successArray array of the success
	 *
	 * @return string
	 */

	protected function _success($successArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), $successArray['route'])
			->doRedirect($successArray['timeout'])
			->success($successArray['message'], $this->_language->get('operation_completed'));
	}

	/**
	 * show the warning
	 *
	 * @since 3.0.0
	 *
	 * @param array $warningArray array of the warning
	 *
	 * @return string
	 */

	protected function _warning($warningArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), $warningArray['route'])
			->doRedirect($warningArray['timeout'])
			->warning($warningArray['message'], $this->_language->get('operation_completed'));
	}

	/**
	 * show the error
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
			->setRoute($this->_language->get('back'), $errorArray['route'])
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
		$captchaValidator = new Validator\Captcha();
		$urlValidator = new Validator\Url();

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
			$messageArray[] = $this->_language->get('email_incorrect');
		}
		if ($postArray['url'] && $urlValidator->validate($postArray['url']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('url_incorrect');
		}
		if (!$postArray['text'])
		{
			$messageArray[] = $this->_language->get('comment_empty');
		}
		if (!$postArray['article'])
		{
			$messageArray[] = $this->_language->get('input_incorrect');
		}
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('captcha_incorrect');
		}
		return $messageArray;
	}

	/**
	 * create the comment
	 *
	 * @since 3.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return boolean
	 */

	protected function _create($createArray = [])
	{
		return Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => $createArray['author'],
				'email' => $createArray['email'],
				'url' => $createArray['url'],
				'text' => $createArray['text'],
				'language' => $createArray['language'],
				'article' => $createArray['article']
			])
			->save();
	}

	/**
	 * send the mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray array of the mail
	 *
	 * @return boolean
	 */

	protected function _mail($mailArray = [])
	{
		$urlArticle = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $mailArray['route'];

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a');
		$linkEmail = $linkElement->copy();
		$linkEmail
			->attr(
			[
				'href' => 'mailto:' . $mailArray['email']
			])
			->text($mailArray['email']);
		$linkUrl = $linkElement->copy();
		$linkUrl
			->attr(
			[
				'href' => $mailArray['url']
			])
			->text($mailArray['url'] ? $mailArray['url'] : $this->_language->get('none'));
		$linkArticle = $linkElement->copy();
		$linkArticle
			->attr(
			[
				'href' => $urlArticle
			])
			->text($urlArticle);

		/* prepare mail */

		$toArray =
		[
			$this->_language->get('author') => Db::getSetting('email')
		];
		$fromArray =
		[
			$mailArray['author'] => $mailArray['email']
		];
		$subject = $this->_language->get('comment_new');
		$bodyArray =
		[
			$this->_language->get('author') . $this->_language->get('colon') . ' ' . $mailArray['author'],
			'<br />',
			$this->_language->get('email') . $this->_language->get('colon') . ' ' . $linkEmail,
			'<br />',
			$this->_language->get('url') . $this->_language->get('colon') . ' ' . $linkUrl,
			'<br />',
			$this->_language->get('article') . $this->_language->get('colon') . ' ' . $linkArticle,
			'<br />',
			$this->_language->get('comment') . $this->_language->get('colon') . ' ' . $mailArray['text']
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}